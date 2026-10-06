<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Snapshot of the provided enums: migration does not depend on future enum/model changes.
    private const DATA = [
        'categories' => [
            ['slug' => 'equipment', 'name' => 'Спорядження', 'icon' => 'equipment', 'sort_order' => 10],
            ['slug' => 'consumables', 'name' => 'Витратники', 'icon' => 'consumables', 'sort_order' => 20],
        ],
        'classes' => [
            ['slug' => 'simple-weapon', 'name' => 'Проста зброя', 'icon' => 'simple-weapon', 'sort_order' => 10, 'parent' => 'equipment'],
            ['slug' => 'martial-weapon', 'name' => 'Військова зброя', 'icon' => 'martial-weapon', 'sort_order' => 20, 'parent' => 'equipment'],
            ['slug' => 'armour', 'name' => 'Обладунки', 'icon' => 'armour', 'sort_order' => 30, 'parent' => 'equipment'],
            ['slug' => 'clothing', 'name' => 'Одяг', 'icon' => 'clothing', 'sort_order' => 40, 'parent' => 'equipment'],
            ['slug' => 'accessories', 'name' => 'Аксесуари', 'icon' => 'accessories', 'sort_order' => 50, 'parent' => 'equipment'],
            ['slug' => 'arrows', 'name' => 'Стріли', 'icon' => 'arrows', 'sort_order' => 60, 'parent' => 'consumables'],
            ['slug' => 'elixirs', 'name' => 'Еліксири', 'icon' => 'elixirs', 'sort_order' => 70, 'parent' => 'consumables'],
            ['slug' => 'potions', 'name' => 'Зілля', 'icon' => 'potions', 'sort_order' => 80, 'parent' => 'consumables'],
            ['slug' => 'scrolls', 'name' => 'Сувої', 'icon' => 'scrolls', 'sort_order' => 90, 'parent' => 'consumables'],
            ['slug' => 'grenades', 'name' => 'Гранати', 'icon' => 'grenades', 'sort_order' => 100, 'parent' => 'consumables'],
            ['slug' => 'coatings', 'name' => 'Покриття', 'icon' => 'coatings', 'sort_order' => 110, 'parent' => 'consumables'],
            ['slug' => 'traps', 'name' => 'Пастки', 'icon' => 'traps', 'sort_order' => 120, 'parent' => 'consumables'],
        ],
        'types' => [
            ['slug' => 'amulets', 'name' => 'Амулети', 'icon' => 'amulets', 'sort_order' => 10, 'parent' => 'accessories'],
            ['slug' => 'rings', 'name' => 'Персні', 'icon' => 'rings', 'sort_order' => 20, 'parent' => 'accessories'],
            ['slug' => 'cloaks', 'name' => 'Плащі', 'icon' => 'cloaks', 'sort_order' => 30, 'parent' => 'accessories'],
            ['slug' => 'shortswords', 'name' => 'Короткі мечі', 'icon' => 'shortswords', 'sort_order' => 40, 'parent' => 'martial-weapon'],
            ['slug' => 'scimitars', 'name' => 'Шаблі', 'icon' => 'scimitars', 'sort_order' => 50, 'parent' => 'martial-weapon'],
            ['slug' => 'war-picks', 'name' => 'Келепи', 'icon' => 'war-picks', 'sort_order' => 60, 'parent' => 'martial-weapon'],
            ['slug' => 'morningstars', 'name' => 'Морґенштерни', 'icon' => 'morningstars', 'sort_order' => 70, 'parent' => 'martial-weapon'],
            ['slug' => 'rapiers', 'name' => 'Рапіри', 'icon' => 'rapiers', 'sort_order' => 80, 'parent' => 'martial-weapon'],
            ['slug' => 'flails', 'name' => 'Ціпи', 'icon' => 'flails', 'sort_order' => 90, 'parent' => 'martial-weapon'],
            ['slug' => 'warhammers', 'name' => 'Бойові молоти', 'icon' => 'warhammers', 'sort_order' => 100, 'parent' => 'martial-weapon'],
            ['slug' => 'battleaxes', 'name' => 'Бойові сокири', 'icon' => 'battleaxes', 'sort_order' => 110, 'parent' => 'martial-weapon'],
            ['slug' => 'longswords', 'name' => 'Довгі мечі', 'icon' => 'longswords', 'sort_order' => 120, 'parent' => 'martial-weapon'],
            ['slug' => 'tridents', 'name' => 'Тризуби', 'icon' => 'tridents', 'sort_order' => 130, 'parent' => 'martial-weapon'],
            ['slug' => 'halberds', 'name' => 'Алебарди', 'icon' => 'halberds', 'sort_order' => 140, 'parent' => 'martial-weapon'],
            ['slug' => 'greatswords', 'name' => 'Великі мечі', 'icon' => 'greatswords', 'sort_order' => 150, 'parent' => 'martial-weapon'],
            ['slug' => 'greataxes', 'name' => 'Великі сокири', 'icon' => 'greataxes', 'sort_order' => 160, 'parent' => 'martial-weapon'],
            ['slug' => 'glaives', 'name' => 'Глефи', 'icon' => 'glaives', 'sort_order' => 170, 'parent' => 'martial-weapon'],
            ['slug' => 'mauls', 'name' => 'Молоти', 'icon' => 'mauls', 'sort_order' => 180, 'parent' => 'martial-weapon'],
            ['slug' => 'pikes', 'name' => 'Піки', 'icon' => 'pikes', 'sort_order' => 190, 'parent' => 'martial-weapon'],
            ['slug' => 'longbows', 'name' => 'Довгі луки', 'icon' => 'longbows', 'sort_order' => 200, 'parent' => 'martial-weapon'],
            ['slug' => 'hand-crossbows', 'name' => 'Малі арбалети', 'icon' => 'hand-crossbows', 'sort_order' => 210, 'parent' => 'martial-weapon'],
            ['slug' => 'heavy-crossbows', 'name' => 'Важкі арбалети', 'icon' => 'heavy-crossbows', 'sort_order' => 220, 'parent' => 'martial-weapon'],
            ['slug' => 'daggers', 'name' => 'Кинджали', 'icon' => 'daggers', 'sort_order' => 230, 'parent' => 'simple-weapon'],
            ['slug' => 'clubs', 'name' => 'Довбні', 'icon' => 'clubs', 'sort_order' => 240, 'parent' => 'simple-weapon'],
            ['slug' => 'light-hammers', 'name' => 'Легкі молоти', 'icon' => 'light-hammers', 'sort_order' => 250, 'parent' => 'simple-weapon'],
            ['slug' => 'sickles', 'name' => 'Серпи', 'icon' => 'sickles', 'sort_order' => 260, 'parent' => 'simple-weapon'],
            ['slug' => 'handaxes', 'name' => 'Сокири', 'icon' => 'handaxes', 'sort_order' => 270, 'parent' => 'simple-weapon'],
            ['slug' => 'maces', 'name' => 'Булави', 'icon' => 'maces', 'sort_order' => 280, 'parent' => 'simple-weapon'],
            ['slug' => 'javelins', 'name' => 'Сулиці', 'icon' => 'javelins', 'sort_order' => 290, 'parent' => 'simple-weapon'],
            ['slug' => 'quarterstaves', 'name' => 'Палиці', 'icon' => 'quarterstaves', 'sort_order' => 300, 'parent' => 'simple-weapon'],
            ['slug' => 'spears', 'name' => 'Списи', 'icon' => 'spears', 'sort_order' => 310, 'parent' => 'simple-weapon'],
            ['slug' => 'greatclubs', 'name' => 'Великі довбні', 'icon' => 'greatclubs', 'sort_order' => 320, 'parent' => 'simple-weapon'],
            ['slug' => 'light-crossbows', 'name' => 'Легкі арбалети', 'icon' => 'light-crossbows', 'sort_order' => 330, 'parent' => 'simple-weapon'],
            ['slug' => 'shortbows', 'name' => 'Короткі луки', 'icon' => 'shortbows', 'sort_order' => 340, 'parent' => 'simple-weapon'],
            ['slug' => 'heavy-armour', 'name' => 'Важкі обладунки', 'icon' => 'heavy-armour', 'sort_order' => 350, 'parent' => 'armour'],
            ['slug' => 'medium-armour', 'name' => 'Середні обладунки', 'icon' => 'medium-armour', 'sort_order' => 360, 'parent' => 'armour'],
            ['slug' => 'light-armour', 'name' => 'Легкі обладунки', 'icon' => 'light-armour', 'sort_order' => 370, 'parent' => 'armour'],
            ['slug' => 'helmets', 'name' => 'Шоломи', 'icon' => 'helmets', 'sort_order' => 380, 'parent' => 'armour'],
            ['slug' => 'shield', 'name' => 'Щити', 'icon' => 'shield', 'sort_order' => 390, 'parent' => 'armour'],
            ['slug' => 'headwear', 'name' => 'Головні убори', 'icon' => 'helmets', 'sort_order' => 400, 'parent' => 'clothing'],
            ['slug' => 'belts', 'name' => 'Пояси', 'icon' => 'belts', 'sort_order' => 410, 'parent' => 'clothing'],
            ['slug' => 'gloves', 'name' => 'Рукавиці', 'icon' => 'gloves', 'sort_order' => 420, 'parent' => 'clothing'],
            ['slug' => 'boots', 'name' => 'Чоботи', 'icon' => 'boots', 'sort_order' => 430, 'parent' => 'clothing'],
            ['slug' => 'darts', 'name' => 'Дротики', 'icon' => 'darts', 'sort_order' => 440, 'parent' => 'simple-weapon'],
            ['slug' => 'slings', 'name' => 'Пращі', 'icon' => 'slings', 'sort_order' => 450, 'parent' => 'simple-weapon'],

            ['slug' => 'lances', 'name' => 'Кавалерійські списи', 'icon' => 'lances', 'sort_order' => 460, 'parent' => 'martial-weapon'],
            ['slug' => 'whips', 'name' => 'Батоги', 'icon' => 'whips', 'sort_order' => 470, 'parent' => 'martial-weapon'],
            ['slug' => 'blowguns', 'name' => 'Духові трубки', 'icon' => 'blowguns', 'sort_order' => 480, 'parent' => 'martial-weapon'],
            ['slug' => 'nets', 'name' => 'Сітки', 'icon' => 'nets', 'sort_order' => 490, 'parent' => 'martial-weapon'],
        ],
    ];

    public function up(): void
    {
        // Validate all legacy values BEFORE changing the schema.
        $categories = array_column(self::DATA['categories'], null, 'slug');
        $classes = array_column(self::DATA['classes'], null, 'slug');
        $types = array_column(self::DATA['types'], null, 'slug');
        $problems = [];
        foreach (DB::table('items')->select('id', 'category', 'item_class', 'type')->orderBy('id')->cursor() as $item) {
            $category = $item->category ?: null;
            $class = $item->item_class ?: null;
            $type = $item->type ?: null;
            $invalid = ($category !== null && !isset($categories[$category]))
                || ($class !== null && (!isset($classes[$class]) || $classes[$class]['parent'] !== $category))
                || ($type !== null && (!isset($types[$type]) || $types[$type]['parent'] !== $class));
            if ($invalid) $problems[] = "#{$item->id}: {$category} / {$class} / {$type}";
        }
        if ($problems !== []) {
            throw new RuntimeException("Невідповідна класифікація предметів. Виправте перед міграцією:\n" . implode("\n", $problems));
        }

        Schema::create('item_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('item_classes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('item_category_id')->constrained('item_categories')->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('item_types', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('item_class_id')->constrained('item_classes')->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::table('items', function (Blueprint $table): void {
            $table->foreignId('item_category_id')->nullable()->constrained('item_categories')->restrictOnDelete();
            $table->foreignId('item_class_id')->nullable()->constrained('item_classes')->restrictOnDelete();
            $table->foreignId('item_type_id')->nullable()->constrained('item_types')->restrictOnDelete();
        });

        DB::transaction(function (): void {
            $now = now();
            foreach (self::DATA['categories'] as $row) {
                DB::table('item_categories')->insert($row + ['created_at' => $now, 'updated_at' => $now]);
            }
            $categories = DB::table('item_categories')->pluck('id', 'slug');
            foreach (self::DATA['classes'] as $row) {
                $row['item_category_id'] = $categories[$row['parent']];
                unset($row['parent']);
                DB::table('item_classes')->insert($row + ['created_at' => $now, 'updated_at' => $now]);
            }
            $classes = DB::table('item_classes')->pluck('id', 'slug');
            foreach (self::DATA['types'] as $row) {
                $row['item_class_id'] = $classes[$row['parent']];
                unset($row['parent']);
                DB::table('item_types')->insert($row + ['created_at' => $now, 'updated_at' => $now]);
            }
            foreach (['category' => ['item_category_id', $categories], 'item_class' => ['item_class_id', $classes], 'type' => ['item_type_id', DB::table('item_types')->pluck('id', 'slug')]] as $old => [$new, $map]) {
                foreach ($map as $slug => $id) DB::table('items')->where($old, $slug)->update([$new => $id]);
            }
        });
    }

    public function down(): void
    {
        // Preserve CURRENT dictionary slugs before removing new columns.
        // The old columns must be VARCHAR/TEXT, not database ENUMs.
        foreach (['item_categories' => ['item_category_id', 'category'], 'item_classes' => ['item_class_id', 'item_class'], 'item_types' => ['item_type_id', 'type']] as $table => [$foreign, $legacy]) {
            DB::table('items')->whereNull($foreign)->update([$legacy => null]);
            foreach (DB::table($table)->select('id', 'slug')->cursor() as $row) {
                DB::table('items')->where($foreign, $row->id)->update([$legacy => $row->slug]);
            }
        }
        Schema::table('items', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('item_type_id');
            $table->dropConstrainedForeignId('item_class_id');
            $table->dropConstrainedForeignId('item_category_id');
        });
        Schema::dropIfExists('item_types');
        Schema::dropIfExists('item_classes');
        Schema::dropIfExists('item_categories');
    }
};
