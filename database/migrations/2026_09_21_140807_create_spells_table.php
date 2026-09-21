<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spells', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('image')->nullable();

            $table->unsignedTinyInteger('level')->default(0);
            $table->string('school');
            $table->boolean('ritual')->default(false);

            $table->json('classes')->nullable();

            $table->string('casting_time');
            $table->string('range');

            $table->boolean('component_verbal')->default(false);
            $table->boolean('component_somatic')->default(false);
            $table->boolean('component_material')->default(false);

            $table->text('material')->nullable();

            $table->string('duration');

            $table->longText('description');

            $table->timestamps();

            $table->index('level');
            $table->index('school');
            $table->index('ritual');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spells');
    }
};
