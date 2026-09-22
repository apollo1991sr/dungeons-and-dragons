<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Enums\ItemAction;
use App\Enums\ItemCategory;
use App\Enums\ItemClass;
use App\Enums\ItemRarity;
use App\Enums\ItemType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Основне
                |--------------------------------------------------------------------------
                */

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
                                string $operation,
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
                            ->disk('items')
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
                            ->preventFilePathTampering()
                            ->columnSpanFull(),

                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | Класифікація
                |--------------------------------------------------------------------------
                */

                Section::make('Класифікація')
                    ->schema([

                        Select::make('category')
                            ->label('Категорія')
                            ->options(
                                self::enumOptions(ItemCategory::cases())
                            )
                            ->required()
                            ->native(false),

                        Select::make('item_class')
                            ->label('Клас')
                            ->options(
                                self::enumOptions(ItemClass::cases())
                            )
                            ->searchable()
                            ->native(false),

                        Select::make('type')
                            ->label('Тип')
                            ->options(
                                self::enumOptions(ItemType::cases())
                            )
                            ->searchable()
                            ->native(false),

                        Select::make('rarity')
                            ->label('Рідкісність')
                            ->options(
                                self::enumOptions(ItemRarity::cases())
                            )
                            ->required()
                            ->native(false),

                        TextInput::make('proficiency')
                            ->label('Спеціалізація')
                            ->placeholder('Без спеціалізації'),

                    ])
                    ->columns(2),

                /*
                  |--------------------------------------------------------------------------
                  | ШКОДА
                  |--------------------------------------------------------------------------
                  */


                Section::make('Шкода')
                    ->schema([

                        Repeater::make('damage')
                            ->label('')
                            ->schema([

                                Textarea::make('value')
                                    ->label('Значення')
                                    ->rows(4)
                                    ->required()
                                    ->columnSpanFull()
                                    ->helperText(
                                        'HTML усередині <span class="text-semibold inline-flex">...</span>'
                                    ),

                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Додати рядок шкоди')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(function (array $state): string {
                                $value = $state['value'] ?? '';

                                $text = trim(
                                    html_entity_decode(
                                        strip_tags($value)
                                    )
                                );

                                return $text !== ''
                                    ? $text
                                    : 'Новий рядок шкоди';
                            })
                            ->columnSpanFull(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Інше
                |--------------------------------------------------------------------------
                */

                Section::make('Інше')
                    ->schema([

                        TextInput::make('price')
                            ->label('Ціна')
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('weight')
                            ->label('Вага, кг')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0),

                        Select::make('action')
                            ->label('Дія')
                            ->options(
                                collect(ItemAction::cases())
                                    ->mapWithKeys(
                                        fn (ItemAction $action) => [
                                            $action->value => $action->label(),
                                        ]
                                    )
                                    ->all()
                            )
                            ->placeholder('Немає')
                            ->native(false),

                        Toggle::make('single_use')
                            ->label('Одноразове')
                            ->default(false),

                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | Статистика
                |--------------------------------------------------------------------------
                */

                Section::make('Статистика')
                    ->description(
                        'КЗ, зачарування та інші характеристики з блоку "Статистика".'
                    )
                    ->schema([

                        Repeater::make('stats')
                            ->label('')
                            ->schema([

                                TextInput::make('label')
                                    ->label('Назва')
                                    ->required()
                                    ->placeholder('Рівень захисту'),

                                TextInput::make('icon')
                                    ->label('Іконка')
                                    ->placeholder('armour-class'),

                                TextInput::make('value')
                                    ->label('Значення')
                                    ->required()
                                    ->placeholder('13 + Модиф. СПР'),

                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Додати характеристику')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(
                                fn (array $state): ?string =>
                                    $state['label'] ?? 'Нова характеристика'
                            ),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Контент сторінки
                |--------------------------------------------------------------------------
                */

                Section::make('Контент')
                    ->columnSpanFull()
                    ->description(
                        'Блоки після заголовка предмета: Опис, Властивості, Застосування тощо.'
                    )
                    ->schema([

                        Textarea::make('description')
                            ->label('Опис')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),

                        Repeater::make('content')
                            ->label('')
                            ->schema([

                                TextInput::make('title')
                                    ->label('Заголовок')
                                    ->required()
                                    ->placeholder('Опис'),

                                Textarea::make('html')
                                    ->label('HTML / текст')
                                    ->required()
                                    ->rows(12)
                                    ->columnSpanFull()
                                    ->helperText(
                                        'Можна використовувати твої CSS-класи: link, accent, fire, damage, distance, burning тощо.'
                                    ),

                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Додати блок')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(
                                fn (array $state): ?string =>
                                    $state['title'] ?? 'Новий блок'
                            ),

                    ]),

            ]);
    }


    private static function enumOptions(array $cases): array
    {
        return collect($cases)
            ->mapWithKeys(
                fn ($case) => [
                    $case->value => $case->label(),
                ]
            )
            ->all();
    }
}
