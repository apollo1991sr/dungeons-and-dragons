<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;
#[Signature('media:prune
        {--delete : Видалити знайдені файли}
        {--hours=24 : Мінімальний вік файлів у годинах}
        {--database=* : Додаткові підключення БД для перевірки}')]
#[Description('Пошук і видалення невикористаних зображень у R2')]
class PruneUnusedImages extends Command
{
    protected $signature = 'media:prune
        {--delete : Видалити знайдені файли}
        {--hours=24 : Мінімальний вік файлів у годинах}
        {--database=* : Додаткові підключення БД для перевірки}';

    protected $description = 'Пошук і видалення невикористаних зображень у R2';

    public function handle(): int
    {
        $hours = filter_var(
            $this->option('hours'),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 0]],
        );

        if ($hours === false) {
            $this->error('--hours має бути цілим невід’ємним числом.');

            return self::FAILURE;
        }

        // Основна база перевіряється завжди.
        $connections = array_values(array_unique([
            config('database.default'),
            ...$this->option('database'),
        ]));

        $this->info('Перевіряємо БД: ' . implode(', ', $connections));

        $disk = Storage::disk('r2');
        $cutoff = now()->subHours($hours)->getTimestamp();

        try {
            [$usedHashes, $content] = $this->references($connections);

            $groups = [];

            // Оригінали зображень.
            foreach (['items', 'spells'] as $directory) {
                foreach ($disk->allFiles($directory) as $path) {
                    if (!preg_match('/\.(png|jpe?g|webp)$/i', $path)) {
                        continue;
                    }

                    $hash = hash('sha256', $path);

                    $groups[$hash]['originals'][] = $path;
                    $groups[$hash]['files'][] = $path;
                }
            }

            // Тільки мініатюри у форматі нашого R2ImageService.
            foreach ($disk->allFiles('thumbnails') as $path) {
                if (!preg_match(
                    '~^thumbnails/([a-f0-9]{64})/[1-9][0-9]*\.webp$~',
                    $path,
                    $matches,
                )) {
                    continue;
                }

                $groups[$matches[1]]['files'][] = $path;
            }

            $candidates = [];
            $recent = 0;

            foreach ($groups as $hash => $group) {
                if ($this->isUsed($hash, $group, $usedHashes, $content)) {
                    continue;
                }

                $files = array_values(array_unique($group['files']));
                $hasRecentFile = false;

                foreach ($files as $path) {
                    if ($disk->lastModified($path) >= $cutoff) {
                        $hasRecentFile = true;
                        break;
                    }
                }

                if ($hasRecentFile) {
                    $recent++;
                    continue;
                }

                $group['files'] = $files;
                $candidates[$hash] = $group;
            }
        } catch (Throwable $exception) {
            // Нічого не видаляємо, якщо перевірка не завершилася.
            $this->error('Перевірку перервано: ' . $exception->getMessage());

            return self::FAILURE;
        }

        $fileCount = 0;

        foreach ($candidates as $group) {
            foreach ($group['files'] as $path) {
                $this->line($path);
                $fileCount++;
            }
        }

        $this->newLine();
        $this->info(
            'Знайдено груп: ' . count($candidates)
            . ". Файлів: {$fileCount}."
        );

        $this->line("Пропущено свіжих груп: {$recent}.");

        if ($fileCount === 0) {
            return self::SUCCESS;
        }

        if (!$this->option('delete')) {
            $this->warn('Це попередній перегляд. Нічого не видалено.');
            $this->line('Для видалення повтори команду з --delete.');

            return self::SUCCESS;
        }

        // Повторно читаємо посилання перед видаленням.
        try {
            [$usedHashes, $content] = $this->references($connections);
        } catch (Throwable $exception) {
            $this->error(
                'Не вдалося повторно перевірити БД: '
                . $exception->getMessage()
            );

            return self::FAILURE;
        }

        $deleted = 0;
        $errors = 0;

        foreach ($candidates as $hash => $group) {
            if ($this->isUsed($hash, $group, $usedHashes, $content)) {
                $this->warn("Група {$hash} вже використовується. Пропускаємо.");
                continue;
            }

            try {
                // Перевіряємо вік ще раз на випадок перезапису файлу.
                foreach ($group['files'] as $path) {
                    if ($disk->lastModified($path) >= $cutoff) {
                        $this->warn("Файл оновлено: {$path}. Групу пропущено.");
                        continue 2;
                    }
                }

                // Видаляємо тільки конкретні перевірені файли.
                foreach ($group['files'] as $path) {
                    if (!$disk->delete($path)) {
                        throw new RuntimeException(
                            "Не вдалося видалити: {$path}"
                        );
                    }

                    $deleted++;
                    $this->line("Видалено: {$path}");
                }
            } catch (Throwable $exception) {
                $errors++;
                $this->error($exception->getMessage());
            }
        }

        $this->info("Видалено файлів: {$deleted}. Помилок: {$errors}.");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function references(array $connections): array
    {
        $usedHashes = [];
        $content = '';

        $tables = [
            'items' => ['id', 'image', 'description', 'content'],
            'spells' => ['id', 'image', 'description'],
        ];

        foreach ($connections as $connection) {
            foreach ($tables as $table => $columns) {
                // Query Builder враховує всі записи, зокрема soft-deleted.
                $records = DB::connection($connection)
                    ->table($table)
                    ->select($columns)
                    ->orderBy('id')
                    ->cursor();

                foreach ($records as $record) {
                    if ($record->image !== null && $record->image !== '') {
                        $path = (string) $record->image;

                        // Очікуємо шлях у R2, а не URL або стару назву файлу.
                        if (!preg_match('~^(items|spells)/.+~', $path)) {
                            throw new RuntimeException(
                                "{$connection}.{$table} #{$record->id}: "
                                . "некоректний шлях image: {$path}"
                            );
                        }

                        $usedHashes[hash('sha256', $path)] = true;
                    }

                    $this->appendContent(
                        $record->description ?? null,
                        $content,
                    );

                    $this->appendContent(
                        $record->content ?? null,
                        $content,
                    );
                }
            }
        }

        return [$usedHashes, $content];
    }

    private function appendContent(mixed $value, string &$content): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (is_string($value)) {
            // Розбираємо JSON content, щоб відновити екрановані символи.
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                $this->appendContent($decoded, $content);
                return;
            }

            $content .= "\n" . rawurldecode(
                    html_entity_decode(
                        $value,
                        ENT_QUOTES | ENT_HTML5,
                        'UTF-8',
                    )
                );

            return;
        }

        if (is_array($value)) {
            foreach ($value as $part) {
                $this->appendContent($part, $content);
            }
        }
    }

    private function isUsed(
        string $hash,
        array $group,
        array $usedHashes,
        string $content,
    ): bool {
        if (isset($usedHashes[$hash])) {
            return true;
        }

        // Контент може прямо посилатися на мініатюру.
        if (str_contains($content, "thumbnails/{$hash}/")) {
            return true;
        }

        foreach ($group['originals'] ?? [] as $path) {
            // Перевіряємо також лише назву: це врахує старі HTML-посилання.
            // Зайвий збіг залишить файл, а не видалить його.
            if (
                str_contains($content, $path)
                || str_contains($content, basename($path))
            ) {
                return true;
            }
        }

        return false;
    }
}
