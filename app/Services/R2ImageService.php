<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use InvalidArgumentException;
use RuntimeException;

class R2ImageService
{
    public function thumbnailPath(string $originalPath, int $size): string
    {
        if ($size < 1) {
            throw new InvalidArgumentException('Image size must be positive.');
        }

        // Includes the full original key and extension to avoid name collisions.
        return 'thumbnails/' . hash('sha256', $originalPath) . '/' . $size . '.webp';
    }

    public function url(string $originalPath, ?int $size = null): string
    {
        return Storage::disk('r2')->url(
            $size === null ? $originalPath : $this->thumbnailPath($originalPath, $size)
        );
    }

    public function generate(string $originalPath, array $sizes, bool $force = false): int
    {
        $disk = Storage::disk('r2');
        $pending = [];

        foreach (array_unique($sizes) as $size) {
            $size = (int) $size;
            $path = $this->thumbnailPath($originalPath, $size);

            if ($force || !$disk->exists($path)) {
                $pending[$size] = $path;
            }
        }

        if ($pending === []) {
            return 0;
        }

        if (!extension_loaded('gd') || !function_exists('imagewebp')) {
            throw new RuntimeException('PHP GD with WebP support is required.');
        }

        $contents = $disk->get($originalPath);

        if (!is_string($contents) || $contents === '') {
            throw new RuntimeException('Cannot read original from R2: ' . $originalPath);
        }

        $manager = new ImageManager(new Driver());

        foreach ($pending as $size => $path) {
            // Read the original for EVERY size: never upscale a smaller thumbnail.
            $image = $manager->read($contents);
            $encoded = $image
                ->contain($size, $size, 'rgba(0, 0, 0, 0)')
                ->toWebp(quality: 85);

            if (!$disk->put($path, (string) $encoded, [
                'ContentType' => 'image/webp',
                'CacheControl' => 'public, max-age=86400',
            ])) {
                throw new RuntimeException('Cannot write thumbnail to R2: ' . $path);
            }

            unset($image, $encoded);
        }

        return count($pending);
    }
}
