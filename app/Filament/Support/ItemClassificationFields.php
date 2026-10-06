<?php

namespace App\Filament\Support;

use App\Models\ItemCategory;
use App\Models\ItemClass;
use App\Models\ItemType;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Validation\Rule;

final class ItemClassificationFields
{
    /** Shared by the item form and the table filter. */
    public static function make(bool $required = true): array
    {
        return [
            Select::make('item_category_id')
                ->label('Категорія')
                ->options(fn (): array => ItemCategory::orderBy('sort_order')->orderBy('name')->pluck('name', 'id')->all())
                ->required($required)
                ->searchable()->native(false)->live()
                ->rules([Rule::exists('item_categories', 'id')])
                ->afterStateUpdated(function (Set $set): void {
                    $set('item_class_id', null);
                    $set('item_type_id', null);
                }),

            Select::make('item_class_id')
                ->label('Клас')
                ->options(fn (Get $get): array => filled($get('item_category_id'))
                    ? ItemClass::where('item_category_id', $get('item_category_id'))->orderBy('sort_order')->orderBy('name')->pluck('name', 'id')->all()
                    : [])
                ->disabled(fn (Get $get): bool => blank($get('item_category_id')))
                // Persist the cleared value even when the select becomes disabled.
                ->dehydrated()
                ->required($required)
                ->searchable()->native(false)->live()
                ->rules(fn (Get $get): array => [Rule::exists('item_classes', 'id')->where('item_category_id', $get('item_category_id'))])
                ->afterStateUpdated(fn (Set $set) => $set('item_type_id', null)),

            Select::make('item_type_id')
                ->label('Тип')
                ->options(fn (Get $get): array => filled($get('item_class_id'))
                    ? ItemType::where('item_class_id', $get('item_class_id'))->orderBy('sort_order')->orderBy('name')->pluck('name', 'id')->all()
                    : [])
                ->disabled(fn (Get $get): bool => blank($get('item_class_id')))
                ->dehydrated()
                ->nullable()
                ->searchable()->native(false)
                ->rules(fn (Get $get): array => [Rule::exists('item_types', 'id')->where('item_class_id', $get('item_class_id'))])
                ->placeholder('Без типу'),
        ];
    }
}
