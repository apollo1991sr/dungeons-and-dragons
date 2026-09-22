<?php

namespace App\Filament\Resources\Spells\Tables;

use App\Enums\SpellClass;
use App\Enums\SpellLevel;
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
                    ->searchable(),

                TextColumn::make('level')
                    ->label('Рівень'),

                TextColumn::make('school')
                    ->label('Школа')
                    ->formatStateUsing(
                        fn ($state): string =>
                        $state instanceof SpellSchool
                            ? $state->label()
                            : SpellSchool::from($state)->label()
                    ),

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

                SelectFilter::make('class')
                    ->label('Клас')
                    ->options(SpellClass::class)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, string $value): Builder =>
                            $query->whereJsonContains('classes', $value)
                        );
                    }),

                SelectFilter::make('level')
                    ->label('Рівень')
                    ->options(SpellLevel::class),

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
            ])

            ->defaultPaginationPageOption(25);
    }
}
