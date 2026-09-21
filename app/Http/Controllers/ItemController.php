<?php

namespace App\Http\Controllers;

use App\Enums\ItemClass;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = $this->sortUk(
            Item::query()->get(),
            fn (Item $item) => $item->name
        );

        $categories = $items
            ->groupBy(fn (Item $item) => $item->category?->value ?? 'other')
            ->map(function (Collection $categoryItems) {

                $category = $categoryItems->first()->category;

                $groups = $categoryItems
                    ->groupBy(fn (Item $item) => $this->getIndexClassLabel($item))
                    ->map(function (Collection $classItems, string $classLabel) {

                        $hasTypes = $classItems->contains(
                            fn (Item $item) => $item->type !== null
                        );

                        /*
                         * Якщо типів немає:
                         *
                         * Гранати:
                         *   Алхімічне полум'я
                         *   Бомба...
                         */
                        if (!$hasTypes) {
                            return [
                                'label' => $classLabel,

                                'types' => collect([
                                    [
                                        'label' => null,
                                        'items' => $classItems->values(),
                                    ],
                                ]),
                            ];
                        }

                        /*
                         * Якщо є типи:
                         *
                         * Аксесуари:
                         *
                         * Амулети:
                         *   ...
                         *
                         * Персні:
                         *   ...
                         */
                        $types = $classItems
                            ->groupBy(
                                fn (Item $item) => $item->type?->label() ?? 'Інше'
                            )
                            ->map(function (Collection $typeItems, string $typeLabel) {
                                return [
                                    'label' => $typeLabel,
                                    'items' => $typeItems->values(),
                                ];
                            })
                            ->values();

                        $types = $this->sortUk(
                            $types,
                            fn (array $type) => $type['label']
                        );

                        return [
                            'label' => $classLabel,
                            'types' => $types,
                        ];
                    })
                    ->values();

                $groups = $this->sortUk(
                    $groups,
                    fn (array $group) => $group['label']
                );

                return [
                    'label' => $category?->label() ?? 'Інше',
                    'groups' => $groups,
                ];
            })
            ->values();

        $categories = $this->sortUk(
            $categories,
            fn (array $category) => $category['label']
        );

        return view('items.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $item = Item::where('slug', $slug)->firstOrFail();

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }

    private function getIndexClassLabel(Item $item): string
    {
        return match ($item->item_class) {
            ItemClass::SimpleWeapon,
            ItemClass::MartialWeapon => 'Зброя',

            null => 'Інше',

            default => $item->item_class->label(),
        };
    }


    private function sortUk(
        Collection $collection,
        callable $value
    ): Collection {
        if (class_exists(\Collator::class)) {

            $collator = new \Collator('uk_UA');

            return $collection
                ->sort(
                    fn ($a, $b) => $collator->compare(
                        $value($a),
                        $value($b)
                    )
                )
                ->values();
        }

        return $collection
            ->sortBy(
                $value,
                SORT_NATURAL | SORT_FLAG_CASE
            )
            ->values();
    }
}
