# Форма та таблиця Filament

Використано API Filament 5 (на твоєму скриншоті assets мають версію 5.9.0) та Filament\Schemas, як у наданій раніше формі.

## Форма ItemForm

Імпорт:

```php
use App\Filament\Support\ItemClassificationFields;
```

В секції «Класифікація» видали три старі Select::make('category'), Select::make('item_class'), Select::make('type') разом з їхніми налаштуваннями. На їхнє місце встав:

```php
...ItemClassificationFields::make(),
```

Наприклад:

```php
Section::make('Класифікація')
    ->schema([
        ...ItemClassificationFields::make(),
        // Тут залиш наявні поля rarity, proficiency, theme тощо.
    ])
    ->columns(2),
```

Категорія і клас обов'язкові. Тип необов'язковий, зокрема для витратників. Зміна категорії очищає клас і тип; зміна класу очищає тип. Під час відкриття редагування збережені значення не очищаються.

## Таблиця ItemsTable

Імпорт:

```php
use App\Filament\Support\ItemClassificationFilter;
```

До існуючого масиву `->filters([...])` додай:

```php
ItemClassificationFilter::make(),
```

Прибери старі enum-фільтри цих трьох полів, якщо вони є. Решту фільтрів залиш.

У таблиці колонки класифікації мають читати relations:

```php
TextColumn::make('itemCategory.name')->label('Категорія'),
TextColumn::make('itemClass.name')->label('Клас'),
TextColumn::make('itemType.name')->label('Тип')->placeholder('—'),
```

Для цих колонок більше не потрібні formatStateUsing() з Enum::from() або label().

Додай `->with(['itemCategory', 'itemClass', 'itemType'])` до існуючого query builder, якщо ці зв'язки використовуєш у власних callbacks. Не додавай другий modifyQueryUsing(), який може замінити попередній.

Фільтр містить три залежні списки. Кнопка «Застосувати» залишається стандартною для твоїх налаштувань таблиці. Зняття загального індикатора скидає всі три поля фільтра.
