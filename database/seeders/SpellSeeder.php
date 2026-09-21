<?php

namespace Database\Seeders;

use App\Enums\SpellClass;
use App\Enums\SpellSchool;
use App\Models\Spell;
use Illuminate\Database\Seeder;
use RuntimeException;

class SpellSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/spells.json');

        if (!file_exists($path)) {
            throw new RuntimeException(
                "Файл spells.json не знайдено: {$path}"
            );
        }

        $spells = json_decode(
            file_get_contents($path),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($spells as $spell) {
            Spell::updateOrCreate(
                [
                    'slug' => $spell['slug'],
                ],
                [
                    'name' => $spell['name'],

                    'image' => $spell['icon'] ?? null,

                    'level' => (int) $spell['level'],

                    'school' => SpellSchool::from(
                        $spell['schoolClass']
                    ),

                    'ritual' => (bool) $spell['ritual'],

                    'classes' => collect($spell['classes'] ?? [])
                        ->map(
                            fn (string $class) =>
                            $this->mapClass($class)
                        )
                        ->values(),

                    'casting_time' => $spell['castingTime'],

                    'range' => $spell['range'],

                    'component_verbal' =>
                        (bool) ($spell['components']['verbal'] ?? false),

                    'component_somatic' =>
                        (bool) ($spell['components']['somatic'] ?? false),

                    'component_material' =>
                        (bool) ($spell['components']['material'] ?? false),

                    'material' =>
                        $spell['components']['materialDescription'] ?? null,

                    'duration' => $spell['duration'],

                    'description' => $spell['description'],
                ]
            );
        }
    }

    private function mapClass(string $class): SpellClass
    {
        return match ($class) {
            'Бард' => SpellClass::Bard,
            'Клірик' => SpellClass::Cleric,
            'Друїд' => SpellClass::Druid,
            'Паладин' => SpellClass::Paladin,
            'Слідопит' => SpellClass::Ranger,
            'Чародій' => SpellClass::Sorcerer,
            'Чаклун' => SpellClass::Warlock,
            'Чарівник' => SpellClass::Wizard,

            default => throw new RuntimeException(
                "Невідомий клас закляття: {$class}"
            ),
        };
    }
}
