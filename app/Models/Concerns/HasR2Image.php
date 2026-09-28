<?php

namespace App\Models\Concerns;

use App\Services\R2ImageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use InvalidArgumentException;

trait HasR2Image
{
    abstract public function imageSizes(): array;

    public static function bootHasR2Image(): void
    {
        static::saving(function ($model): void {
            if ($model->isDirty('image') && filled($model->image)) {
                // FileUpload has already placed the original in R2 by this point.
                // A generation failure prevents saving a new DB path to incomplete variants.
                app(R2ImageService::class)->generate(
                    $model->image,
                    $model->imageSizes(),
                    force: true,
                );
            }
        });
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => filled($this->image)
                ? app(R2ImageService::class)->url($this->image)
                : null,
        );
    }

    public function imageUrlFor(int $size): ?string
    {
        if (!in_array($size, $this->imageSizes(), true)) {
            throw new InvalidArgumentException('Unsupported image size: ' . $size);
        }

        return filled($this->image)
            ? app(R2ImageService::class)->url($this->image, $size)
            : null;
    }

    public function imageSrcset(int $size): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        return $this->imageUrlFor($size) . ' 1x, '
            . $this->imageUrlFor($size * 2) . ' 2x';
    }
}
