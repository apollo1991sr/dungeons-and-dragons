<?php

namespace App\Filament\Resources\Items\Tables;

use App\Models\Item;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Support\ItemClassificationFilter;
use Filament\Actions\Action;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->with(['itemCategory', 'itemClass', 'itemType'])
                    ->orderBy('item_category_id')
                    ->orderByRaw('item_class_id IS NULL')
                    ->orderBy('item_class_id')
                    ->orderByRaw('item_type_id IS NULL')
                    ->orderBy('item_type_id')
                    ->orderBy('name')
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Зображення')
                    ->getStateUsing(
                        fn (Item $record): ?string => $record->imageUrlFor(40)
                    )
                    ->extraImgAttributes(
                        fn (Item $record): array => [
                            'srcset' => $record->imageSrcset(40),
                            'alt' => $record->name,
                            'loading' => 'lazy',
                        ]
                    )
                    ->square()
                    ->imageSize(40),
                TextColumn::make('itemCategory.name')->label('Категорія'),
                TextColumn::make('itemClass.name')->label('Клас'),
                TextColumn::make('itemType.name')->label('Тип')->placeholder('—'),
                TextColumn::make('rarity')
                    ->label('Рідкісність')
                    ->badge()
                    ->sortable(),
            ])
            ->groups([
                Group::make('item_category_id')
                    ->label('Категорія')
                    ->getTitleFromRecordUsing(
                        fn (Item $record): string =>
                            $record->itemCategory?->name ?? 'Без категорії'
                    ),

                Group::make('item_class_id')
                    ->label('Клас')
                    ->getTitleFromRecordUsing(
                        fn (Item $record): string =>
                            $record->itemClass?->name ?? 'Без класу'
                    ),

                Group::make('item_type_id')
                    ->label('Тип')
                    ->getTitleFromRecordUsing(
                        fn (Item $record): string =>
                            $record->itemType?->name ?? 'Без типу'
                    ),
            ])

            ->filters([
                ItemClassificationFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('printCard')
                    ->label('Картка для друку')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(
                        fn (Item $record): string =>
                        route('items.card', $record->slug)
                    )
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
