# Зміни App\Models\Item

Не замінюй всю модель: залиш R2, imageSizes(), trimming, casts рідкісності тощо.

1. Додай імпорт і trait:

```php
use App\Models\Concerns\HasItemClassification;
```

У класі:

```php
use HasItemClassification;
```

2. У `$fillable` заміни `category`, `item_class`, `type` на:

```php
'item_category_id',
'item_class_id',
'item_type_id',
```

3. Видали лише ці три casts:

```php
'category' => ItemCategory::class,
'item_class' => ItemClass::class,
'type' => ItemType::class,
```

Видали відповідні імпорти `App\Enums\ItemCategory`, `ItemClass`, `ItemType`, якщо модель більше ніде їх не використовує.

Нові поля можна додати до casts:

```php
'item_category_id' => 'integer',
'item_class_id' => 'integer',
'item_type_id' => 'integer',
```

Старі колонки залишаються як перехідна копія slug. Trait синхронізує їх при звичайному save(). Не заповнюй їх напряму. Нові джерела даних — itemCategory/itemClass/itemType та відповідні _id.

Збереження через DB::table(), масові update() і saveQuietly() обходить перевірку trait. Імпортери/seeders мають передавати нові _id та самі дотримуватись ієрархії. Не міняй батьків довідників через SQL без перевірки предметів: звичайні FK гарантують існування записів, але не узгодженість трьох рівнів між собою.
