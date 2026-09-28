<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Throwable;
use finfo;

class UploadMediaToR2 extends Command
{
    protected $signature = 'media:upload-r2
        {--update-db : Update image paths in items and spells after verifying uploads}';

    protected $description = 'Copy public/images/items and public/images/spells to R2';

    public function handle(): int
    {
        $updateDb = (bool) $this->option('update-db');
        $disk = Storage::disk('r2');
        $uploaded = $existing = $updated = $failed = 0;

        foreach (['items', 'spells'] as $directory) {
            $root = public_path('images/' . $directory);
            if (!is_dir($root)) {
                $this->error("Missing directory: {$root}");
                $failed++;
                continue;
            }

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                if (!$file->isFile() || $file->isLink()) {
                    continue;
                }

                $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
                if (preg_match('~(^|/)\.~', $relative)) {
                    continue;
                }
                $key = $directory . '/' . $relative;

                try {
                    $localHash = hash_file('sha256', $file->getPathname());
                    if ($localHash === false) {
                        throw new RuntimeException('Cannot hash local file');
                    }

                    if ($disk->exists($key)) {
                        if ($this->remoteHash($disk, $key) !== $localHash) {
                            throw new RuntimeException('Different file already exists in R2; not overwritten');
                        }
                        $existing++;
                        $this->line("EXISTS {$key}");
                    } else {
                        $stream = fopen($file->getPathname(), 'rb');
                        if ($stream === false) {
                            throw new RuntimeException('Cannot open local file');
                        }
                        try {
                            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file->getPathname());
                            if (!$disk->put($key, $stream, [
                                'ContentType' => $mime ?: 'application/octet-stream',
                            ])) {
                                throw new RuntimeException('Upload failed');
                            }
                        } finally {
                            fclose($stream);
                        }

                        if ($this->remoteHash($disk, $key) !== $localHash) {
                            throw new RuntimeException('Uploaded object failed SHA-256 verification');
                        }
                        $uploaded++;
                        $this->info("UPLOADED {$key}");
                    }

                    if ($updateDb) {
                        // Exact PHP comparison avoids case-insensitive DB collations.
                        // Query builder deliberately bypasses model events/file deletion hooks.
                        $rows = DB::table($directory)->where('image', $relative)->get(['id', 'image']);
                        foreach ($rows as $row) {
                            if ($row->image === $relative) {
                                $updated += DB::table($directory)
                                    ->where('id', $row->id)
                                    ->where('image', $relative)
                                    ->update(['image' => $key]);
                            }
                        }
                    }
                } catch (Throwable $exception) {
                    $failed++;
                    $this->error("ERROR {$key}: {$exception->getMessage()}");
                }
            }
        }

        $this->newLine();
        $this->info("Uploaded: {$uploaded}; identical existing: {$existing}; DB rows updated: {$updated}; errors: {$failed}");
        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function remoteHash(FilesystemAdapter $disk, string $key): string
    {
        $stream = $disk->readStream($key);
        if (!is_resource($stream)) {
            throw new RuntimeException("Cannot read R2 object: {$key}");
        }
        try {
            $hash = hash_init('sha256');
            if (hash_update_stream($hash, $stream) === false) {
                throw new RuntimeException("Cannot hash R2 object: {$key}");
            }
            return hash_final($hash);
        } finally {
            fclose($stream);
        }
    }
}
