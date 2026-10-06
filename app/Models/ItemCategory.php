<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemCategory extends Model
{
    protected $fillable = ['slug', 'name', 'icon', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function label(): string { return $this->name; }
    public function icon(): string { return $this->getAttribute('icon') ?: $this->slug; }

    public function classes(): HasMany { return $this->hasMany(ItemClass::class); }
    public function items(): HasMany { return $this->hasMany(Item::class); }
}
