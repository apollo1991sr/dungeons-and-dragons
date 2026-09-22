<?php

namespace App\Models;

use App\Enums\ItemAction;
use App\Enums\ItemCategory;
use App\Enums\ItemClass;
use App\Enums\ItemRarity;
use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'category',
        'item_class',
        'type',
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
            'category' => ItemCategory::class,
            'item_class' => ItemClass::class,
            'type' => ItemType::class,
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
}
