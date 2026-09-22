<?php

namespace App\Filament\Resources\Spells\Schemas;

use App\Enums\SpellClass;
use App\Enums\SpellLevel;
use App\Enums\SpellSchool;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SpellForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Основне')
                    ->schema([

                        TextInput::make('name')
                            ->label('Назва')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (
                                ?string $state,
                                Set $set,
                                string $operation
                            ): void {
                                if ($operation !== 'create') {
                                    return;
                                }

                                $set('slug', Str::slug($state ?? ''));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        FileUpload::make('image')
                            ->label('Зображення')
                            ->disk('spells')
                            ->visibility('public')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(5120)
                            ->imagePreviewHeight('250')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),

                    ])
                    ->columns(2),


                Section::make('Класифікація')
                    ->schema([

                        Select::make('level')
                            ->label('Рівень')
                            ->options(SpellLevel::class)
                            ->required()
                            ->native(false),

                        Select::make('school')
                            ->label('Школа')
                            ->options(SpellSchool::class)
                            ->required()
                            ->native(false),

                        Toggle::make('ritual')
                            ->label('Ритуал')
                            ->default(false),

                        Select::make('classes')
                            ->label('Класи')
                            ->multiple()
                            ->options(SpellClass::class)
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->columnSpanFull(),

                    ])
                    ->columns(2),


                Section::make('Виконання')
                    ->schema([

                        TextInput::make('casting_time')
                            ->label('Час виконання')
                            ->placeholder('1 дія')
                            ->required(),

                        TextInput::make('range')
                            ->label('Дистанція')
                            ->placeholder('На себе')
                            ->required(),

                        TextInput::make('duration')
                            ->label('Тривалість')
                            ->placeholder('Концентрація, не довше 10 хвилин')
                            ->required(),

                    ])
                    ->columns(2),


                Section::make('Компоненти')
                    ->schema([

                        Toggle::make('component_verbal')
                            ->label('С')
                            ->helperText('Словесний'),

                        Toggle::make('component_somatic')
                            ->label('Т')
                            ->helperText('Тілесний'),

                        Toggle::make('component_material')
                            ->label('М')
                            ->helperText('Матеріальний')
                            ->live()
                            ->afterStateUpdated(
                                function (bool $state, Set $set): void {
                                    if (!$state) {
                                        $set('material', null);
                                    }
                                }
                            ),

                        TextInput::make('material')
                            ->label('Матеріал')
                            ->placeholder('лист тису')
                            ->helperText(
                                'Тільки текст усередині дужок, без самих дужок.'
                            )
                            ->visible(
                                fn (Get $get): bool =>
                                (bool) $get('component_material')
                            )
                            ->columnSpanFull(),

                    ])
                    ->columns(3),


                Section::make('Опис')
                    ->schema([

                        Textarea::make('description')
                            ->label('')
                            ->rows(18)
                            ->required()
                            ->columnSpanFull()
                            ->helperText(
                                'HTML дозволений. Вміст зберігається без переформатування.'
                            ),

                    ]),

            ]);
    }
}
