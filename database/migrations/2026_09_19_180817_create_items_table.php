<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Основне
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();

            // Класифікація
            $table->string('category')->nullable();
            $table->string('item_class')->nullable();
            $table->string('type')->nullable();
            $table->string('proficiency')->nullable();
            $table->enum('rarity', [
                'common',
                'uncommon',
                'rare',
                'very-rare',
                'legend',
            ])->nullable();

            // Інше
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();

            // Додаткові характеристики,
            // наприклад КЗ, зачарування, шкода тощо
            $table->json('stats')->nullable();

            // Додаткові властивості infobox,
            // які не підходять під стандартні поля
            $table->json('attributes')->nullable();

            // Основний контент сторінки:
            // Опис, Властивості, Застосування тощо
            $table->json('content')->nullable();

            $table->timestamps();

            $table->index('category');
            $table->index('item_class');
            $table->index('type');
            $table->index('rarity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
