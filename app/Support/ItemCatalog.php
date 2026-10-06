<?php

namespace App\Support;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemClass;
use App\Models\ItemType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class ItemCatalog
{
    public static function filters(Request $request): array
    {
        $input = $request->only(['item_category_id', 'item_class_id', 'item_type_id']);
        // Reject arrays before using parent values in SQL validation rules.
        foreach ($input as $value) {
            abort_unless($value === null || is_scalar($value), 422, 'Некоректний фільтр.');
        }
        $validator = Validator::make($input, [
            'item_category_id' => ['nullable', 'required_with:item_class_id,item_type_id', 'integer', Rule::exists('item_categories', 'id')],
            'item_class_id' => ['nullable', 'required_with:item_type_id', 'integer', Rule::exists('item_classes', 'id')->where('item_category_id', $input['item_category_id'] ?? null)],
            'item_type_id' => ['nullable', 'integer', Rule::exists('item_types', 'id')->where('item_class_id', $input['item_class_id'] ?? null)],
        ]);
        abort_if($validator->fails(), 422, 'Некоректний фільтр класифікації. Відкрийте список предметів без параметрів.');
        $data = $validator->validated();
        $selected = [];
        foreach (['item_category_id', 'item_class_id', 'item_type_id'] as $key) {
            $selected[$key] = filled($data[$key] ?? null) ? (int) $data[$key] : null;
        }
        return [
            'selected' => $selected,
            'categories' => ItemCategory::orderBy('sort_order')->orderBy('name')->get(),
            'classes' => $selected['item_category_id']
                ? ItemClass::where('item_category_id', $selected['item_category_id'])->orderBy('sort_order')->orderBy('name')->get()
                : collect(),
            'types' => $selected['item_class_id']
                ? ItemType::where('item_class_id', $selected['item_class_id'])->orderBy('sort_order')->orderBy('name')->get()
                : collect(),
        ];
    }

    public static function query(array $selected): Builder
    {
        $query = Item::query()->with(['itemCategory', 'itemClass', 'itemType']);
        foreach (['item_category_id', 'item_class_id', 'item_type_id'] as $column) {
            if (filled($selected[$column] ?? null)) $query->where($column, $selected[$column]);
        }
        return $query;
    }

    /** Same category/groups/types structure as the provided public Blade list. */
    public static function groups(Collection $items): array
    {
        return $items->groupBy('item_category_id')
            ->sortBy(fn (Collection $group) => $group->first()->itemCategory?->sort_order ?? PHP_INT_MAX)
            ->map(fn (Collection $categoryItems): array => [
                'label' => $categoryItems->first()->itemCategory?->name ?? 'Без категорії',
                'groups' => $categoryItems->groupBy('item_class_id')
                    ->sortBy(fn (Collection $group) => $group->first()->itemClass?->sort_order ?? PHP_INT_MAX)
                    ->map(fn (Collection $classItems): array => [
                        'label' => $classItems->first()->itemClass?->name ?? 'Без класу',
                        'types' => $classItems->groupBy('item_type_id')
                            ->sortBy(fn (Collection $group) => $group->first()->itemType?->sort_order ?? PHP_INT_MAX)
                            ->map(fn (Collection $typeItems): array => [
                                'label' => $typeItems->first()->itemType?->name,
                                'items' => $typeItems->values(),
                            ])->values()->all(),
                    ])->values()->all(),
            ])->values()->all();
    }
}
