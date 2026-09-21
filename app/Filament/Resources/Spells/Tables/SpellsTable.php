<?php

namespace App\Filament\Resources\Spells\Tables;

use App\Enums\SpellSchool;
use App\Models\Spell;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SpellsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->orderBy('level')
                    ->orderBy('school')
                    ->orderBy('name')
            )

            ->columns([

                ImageColumn::make('image')
                    ->label('')
                    ->disk('spells')
                    ->visibility('public')
                    ->square()
                    ->imageSize(50),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('level')
                    ->label('Рівень')
                    ->formatStateUsing(
                        fn (int $state): string =>
                        $state === 0
                            ? 'Замовляння'
                            : $state . ' рівень'
                    )
                    ->sortable(),

                TextColumn::make('school')
                    ->label('Школа')
                    ->formatStateUsing(
                        fn ($state): string =>
                        $state instanceof SpellSchool
                            ? $state->label()
                            : SpellSchool::from($state)->label()
                    )
                    ->sortable(),

                IconColumn::make('ritual')
                    ->label('Ритуал')
                    ->boolean(),

                TextColumn::make('classes')
                    ->label('Класи')
                    ->getStateUsing(
                        fn (Spell $record): array =>
                            $record->classes
                                ?->map(fn ($class) => $class->label())
                                ->all() ?? []
                    )
                    ->badge(),

            ])

            ->filters([

                SelectFilter::make('level')
                    ->label('Рівень')
                    ->options([
                        0 => 'Замовляння',
                        1 => '1 рівень',
                        2 => '2 рівень',
                        3 => '3 рівень',
                        4 => '4 рівень',
                        5 => '5 рівень',
                        6 => '6 рівень',
                        7 => '7 рівень',
                        8 => '8 рівень',
                        9 => '9 рівень',
                    ]),

                SelectFilter::make('school')
                    ->label('Школа')
                    ->options(SpellSchool::class),

                TernaryFilter::make('ritual')
                    ->label('Ритуал'),

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
