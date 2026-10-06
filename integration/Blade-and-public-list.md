# Blade, публічний список і друк

## Заміна enum-звернень

У всіх Blade та PHP, де показується класифікація предмета:

| Було | Стало |
|---|---|
| `$item->category?->label()` | `$item->itemCategory?->label()` |
| `$item->item_class?->label()` | `$item->itemClass?->label()` |
| `$item->type?->label()` | `$item->itemType?->label()` |
| `$item->category?->icon()` | `$item->itemCategory?->icon()` |
| `$item->item_class?->icon()` | `$item->itemClass?->icon()` |
| `$item->type?->icon()` | `$item->itemType?->icon()` |
| `$item->category?->value` | `$item->itemCategory?->slug` |
| `$item->item_class?->value` | `$item->itemClass?->slug` |
| `$item->type?->value` | `$item->itemType?->slug` |

Якщо перед викликом вже є перевірка `if`, `?->` необов'язковий. Порівняння з enum-case заміни порівнянням slug, наприклад:

```php
$item->itemClass?->slug === 'armour'
```

ItemRarity та інші enum залишаються без змін.

## Спільний шаблон картки

У resources/views/items/partials/card.blade.php у першому foreach заміни масив:

```php
foreach (['itemCategory' => 'Категорія', 'itemClass' => 'Клас', 'itemType' => 'Тип'] as $field => $label) {
    if ($item->{$field}) {
        $facts->push([
            'label' => $label,
            'value' => $item->{$field}->label(),
            'icon' => $item->{$field}->icon(),
        ]);
    }
}
```

Завдяки спільному partial це змінить і одиночну картку, і чотири на А4. CSS, розміри, R2 не змінюються.

## Контролер публічного списку

У поточному ItemController додай імпорти:

```php
use App\Support\ItemCatalog;
use Illuminate\Http\Request;
```

Якщо твій index() лише формує `$categories` для раніше наданого items/index.blade.php, його тіло можна замінити:

```php
public function index(Request $request)
{
    $classification = ItemCatalog::filters($request);
    $items = ItemCatalog::query($classification['selected'])->orderBy('name')->get();
    $categories = ItemCatalog::groups($items);

    return view('items.index', compact('categories', 'classification'));
}
```

Якщо є додаткова логіка доступу, пошуку або інші view-дані — збережи її при інтеграції. Не замінюй решту контролера та методи show/card.

У items/index.blade.php перед списком категорій:

```blade
@include('items.partials.classification-filter')

@if(empty($categories))
    <p>За обраними фільтрами предметів не знайдено.</p>
@endif
```

Твій існуючий цикл `$categories → groups → types → items` залишається, структура даних така сама. Назви та порядок тепер беруться з таблиць довідників. Старий JS пошуку фільтруватиме предмети вже всередині обраної класифікації.

За бажанням додай у items-index.css:

```css
.items-classification-filter {
    display: flex;
    flex-wrap: wrap;
    align-items: end;
    gap: 12px;
    margin-bottom: 20px;
}
.items-classification-filter label {
    display: grid;
    gap: 6px;
}
.items-classification-filter select {
    max-width: 100%;
    padding: 6px 10px;
}
```

## Завантаження зв'язків без зайвих запитів

У show/card після отримання предмета:

```php
$item->loadMissing(['itemCategory', 'itemClass', 'itemType']);
```

У нашому ItemPrintController заміни отримання `$selected` на:

```php
$selected = Item::query()
    ->with(['itemCategory', 'itemClass', 'itemType'])
    ->whereIn('id', array_column($rows, 'id'))
    ->get()
    ->keyBy('id');
```

## Пошук залишків старої логіки

З кореня проєкту:

```bash
rg 'ItemCategory|ItemClass|ItemType|item_class|category->|type->' app resources database
```

Перевір старі `::cases()`, `::from()`, `::tryFrom()`, enum-порівняння, casts, наповнення seeders. Нові `App\Models\Item*` не плутай зі старими `App\Enums\Item*`.
