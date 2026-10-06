<?php

namespace App\Models;

use App\Enums\ItemAction;
use App\Enums\ItemRarity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasR2Image;
use App\Models\Concerns\HasItemClassification;

class Item extends Model
{
    use HasFactory, HasR2Image, HasItemClassification;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'item_category_id',
        'item_class_id',
        'item_type_id',
        'proficiency',
        'rarity',
        'theme',
        'price',
        'weight',
        'action',
        'single_use',
        'stats',
        'damage',
        'description',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'item_category_id' => 'integer',
            'item_class_id' => 'integer',
            'item_type_id' => 'integer',
            'rarity' => ItemRarity::class,
            'action' => ItemAction::class,

            'single_use' => 'boolean',

            'price' => 'decimal:2',
            'weight' => 'decimal:2',

            'stats' => 'array',
            'damage' => 'array',
            'content' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Item $item): void {
            $fields = [
                'name',
                'slug',
                'image',
                'proficiency',

                'stats',
                'damage',
                'attributes',
                'description',
                'content',
            ];

            foreach ($fields as $field) {
                $item->{$field} = self::trimRecursive($item->{$field});
            }
        });
    }

    private static function trimRecursive(mixed $value): mixed
    {
        if (is_string($value)) {
            return trim($value);
        }

        if (is_array($value)) {
            return array_map(
                fn ($item) => self::trimRecursive($item),
                $value
            );
        }

        return $value;
    }

    public function imageSizes(): array
    {
        return [40, 80, 276, 552];
    }
}
