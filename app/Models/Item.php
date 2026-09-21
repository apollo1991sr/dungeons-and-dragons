<?php

namespace App\Models;

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
        'stats',
        'attributes',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'category' => ItemCategory::class,
            'item_class' => ItemClass::class,
            'type' => ItemType::class,
            'rarity' => ItemRarity::class,

            'price' => 'decimal:2',
            'weight' => 'decimal:2',

            'stats' => 'array',
            'attributes' => 'array',
            'content' => 'array',
        ];
    }
}
