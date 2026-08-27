<?php

namespace App\Http\Controllers;

use Collator;
use Illuminate\Support\Facades\File;

class SpellController extends Controller
{
    public function index()
    {
        $spells = $this->getSpells();

        $classes = [
            'Бард',
            'Друїд',
            'Клірик',
            'Паладин',
            'Слідопит',
            'Чаклун',
            'Чарівник',
            'Чародій',
        ];

        $selectedClass = request('class');

        if (
            $selectedClass &&
            in_array($selectedClass, $classes, true)
        ) {
            $spells = array_filter(
                $spells,
                fn (array $spell) =>
                in_array(
                    $selectedClass,
                    $spell['classes'] ?? [],
                    true
                )
            );
        }

        $collator = new Collator('uk_UA');

        usort($spells, function (array $a, array $b) use ($collator) {
            if ($a['level'] !== $b['level']) {
                return $a['level'] <=> $b['level'];
            }

            return $collator->compare(
                $a['name'],
                $b['name']
            );
        });

        $spells = collect($spells)
            ->groupBy('level');

        return view(
            'spells.index',
            compact(
                'spells',
                'classes',
                'selectedClass'
            )
        );
    }

    public function show(string $slug)
    {
        $spell = collect($this->getSpells())
            ->firstWhere('slug', $slug);

        abort_unless($spell, 404);

        return view('spells.show', compact('spell'));
    }

    private function getSpells(): array
    {
        $path = resource_path('data/spells.json');

        return json_decode(
            File::get($path),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
