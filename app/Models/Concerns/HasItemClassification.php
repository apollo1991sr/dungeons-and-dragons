<?php

namespace App\Models\Concerns;

use App\Models\ItemCategory;
use App\Models\ItemClass;
use App\Models\ItemType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

trait HasItemClassification
{
    public function itemCategory(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function itemClass(): BelongsTo
    {
        return $this->belongsTo(ItemClass::class, 'item_class_id');
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public static function bootHasItemClassification(): void
    {
        static::saving(function ($item): void {
            // Query fresh parents: loaded relationships can be stale after ID changes.
            $category = ItemCategory::find($item->item_category_id);
            $class = ItemClass::find($item->item_class_id);
            $type = filled($item->item_type_id) ? ItemType::find($item->item_type_id) : null;
            $errors = [];
            if (!$category) $errors['item_category_id'] = 'Оберіть категорію.';
            if (!$class || !$category || (int) $class->item_category_id !== (int) $category->id) {
                $errors['item_class_id'] = 'Клас має належати обраній категорії.';
            }
            if (filled($item->item_type_id) && (!$type || !$class || (int) $type->item_class_id !== (int) $class->id)) {
                $errors['item_type_id'] = 'Тип має належати обраному класу.';
            }
            if ($errors !== []) throw ValidationException::withMessages($errors);

            // Keep old slug columns during the transition; IDs are the source of truth.
            $item->category = $category->slug;
            $item->item_class = $class->slug;
            $item->type = $type?->slug;
            $item->setRelation('itemCategory', $category);
            $item->setRelation('itemClass', $class);
            $item->setRelation('itemType', $type);
        });
    }
}
