<?php

namespace App\Http\Controllers;

use App\Enums\SpellClass;
use App\Models\Spell;
use Collator;
use Illuminate\View\View;

class SpellController extends Controller
{
    public function index(): View
    {
        $classes = SpellClass::cases();

        $selectedClass = request('class');

        $selectedClassEnum = $selectedClass
            ? SpellClass::tryFrom($selectedClass)
            : null;

        $query = Spell::query();

        if ($selectedClassEnum) {
            $query->whereJsonContains(
                'classes',
                $selectedClassEnum->value
            );
        }

        $spells = $query->get();

        $collator = new Collator('uk_UA');

        $spells = $spells
            ->sort(function (Spell $a, Spell $b) use ($collator) {
                if ($a->level !== $b->level) {
                    return $a->level->value <=> $b->level->value;
                }

                return $collator->compare(
                    $a->name,
                    $b->name
                );
            })
            ->groupBy(
                fn (Spell $spell) => $spell->level->value
            );

        return view(
            'spells.index',
            compact(
                'spells',
                'classes',
                'selectedClass'
            )
        );
    }

    public function show(string $slug): View
    {
        $spell = Spell::where('slug', $slug)
            ->firstOrFail();

        return view(
            'spells.show',
            compact('spell')
        );
    }
}
