<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemType extends Model
{
    protected $fillable = ['item_class_id', 'slug', 'name', 'icon', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function label(): string { return $this->name; }
    public function icon(): string { return $this->getAttribute('icon') ?: $this->slug; }

    public function itemClass(): BelongsTo { return $this->belongsTo(ItemClass::class, 'item_class_id'); }
    public function items(): HasMany { return $this->hasMany(Item::class); }
}
