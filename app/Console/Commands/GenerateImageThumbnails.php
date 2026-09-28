<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\Spell;
use App\Services\R2ImageService;
use Illuminate\Console\Command;
use Throwable;

class GenerateImageThumbnails extends Command
{
    protected $signature = 'media:thumbnails
        {--type=all : all, items or spells}
        {--force : Regenerate existing thumbnails}';

    protected $description = 'Generate WebP thumbnails from original images in R2';

    public function handle(R2ImageService $images): int
    {
        $type = $this->option('type');

        if (!in_array($type, ['all', 'items', 'spells'], true)) {
            $this->error('--type must be all, items or spells.');
            return self::FAILURE;
        }

        $generated = $processed = $failed = 0;

        foreach (['items' => Item::class, 'spells' => Spell::class] as $name => $model) {
            if ($type !== 'all' && $type !== $name) {
                continue;
            }

            $records = $model::query()
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->lazyById(100);

            foreach ($records as $record) {
                try {
                    $count = $images->generate(
                        $record->image,
                        $record->imageSizes(),
                        force: (bool) $this->option('force'),
                    );
                    $generated += $count;
                    $processed++;
                    $this->line("{$name} #{$record->getKey()}: {$count} generated");
                } catch (Throwable $exception) {
                    $failed++;
                    $this->error("{$name} #{$record->getKey()}: {$exception->getMessage()}");
                }
            }
        }

        $this->info("Records: {$processed}; generated: {$generated}; errors: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
