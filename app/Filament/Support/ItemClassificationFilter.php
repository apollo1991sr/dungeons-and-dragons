<?php

namespace App\Filament\Support;

use App\Models\ItemCategory;
use App\Models\ItemClass;
use App\Models\ItemType;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

final class ItemClassificationFilter
{
    public static function make(): Filter
    {
        return Filter::make('classification')
            ->label('Класифікація')
            ->schema(ItemClassificationFields::make(required: false))
            ->query(function (Builder $query, array $data): Builder {
                // AND conditions also prevent a tampered parent/child combination from matching.
                foreach (['item_category_id', 'item_class_id', 'item_type_id'] as $column) {
                    if (filled($data[$column] ?? null)) {
                        $query->where($query->getModel()->qualifyColumn($column), $data[$column]);
                    }
                }
                return $query;
            })
            ->indicateUsing(function (array $data): ?string {
                $labels = [];
                foreach (['item_category_id' => ItemCategory::class, 'item_class_id' => ItemClass::class, 'item_type_id' => ItemType::class] as $key => $model) {
                    if (filled($data[$key] ?? null)) {
                        $labels[] = $model::find($data[$key])?->name ?? 'Недоступне значення';
                    }
                }
                return $labels === [] ? null : 'Класифікація: ' . implode(' → ', $labels);
            });
    }
}
