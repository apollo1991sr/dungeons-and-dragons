<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemClass extends Model
{
    protected $fillable = ['item_category_id', 'slug', 'name', 'icon', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function label(): string { return $this->name; }
    public function icon(): string { return $this->getAttribute('icon') ?: $this->slug; }

    public function category(): BelongsTo { return $this->belongsTo(ItemCategory::class, 'item_category_id'); }
    public function types(): HasMany { return $this->hasMany(ItemType::class); }
    public function items(): HasMany { return $this->hasMany(Item::class); }
}
