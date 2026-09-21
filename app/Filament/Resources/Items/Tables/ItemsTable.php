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

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->orderBy('category')
                    ->orderByRaw('item_class IS NULL')
                    ->orderBy('item_class')
                    ->orderByRaw('type IS NULL')
                    ->orderBy('type')
                    ->orderBy('name')
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Зображення')
                    ->disk('items')
                    ->visibility('public')
                    ->square()
                    ->imageSize(40),
                TextColumn::make('category')
                    ->badge()
                    ->searchable(),
                TextColumn::make('item_class')
                    ->badge()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('proficiency')
                    ->searchable(),
                TextColumn::make('rarity')
                    ->label('Рідкісність')
                    ->badge()
                    ->sortable(),
                TextColumn::make('price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('weight')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([

                Group::make('category')
                    ->label('Категорія')
                    ->getTitleFromRecordUsing(
                        fn (Item $record): string =>
                            $record->category?->label() ?? 'Без категорії'
                    ),

                Group::make('item_class')
                    ->label('Клас')
                    ->getTitleFromRecordUsing(
                        fn (Item $record): string =>
                            $record->item_class?->label() ?? 'Інше'
                    ),

                Group::make('type')
                    ->label('Тип')
                    ->getTitleFromRecordUsing(
                        fn (Item $record): string =>
                            $record->type?->label() ?? 'Без типу'
                    ),

            ])

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
