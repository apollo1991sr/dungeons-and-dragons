<?php

namespace App\Http\Controllers;

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
            Item::query()
                ->with(['itemCategory', 'itemClass', 'itemType'])
                ->get(),
            fn (Item $item) => $item->name
        );

        $categories = $items
            ->groupBy(
                fn (Item $item) => $item->item_category_id ?? 'other'
            )
            ->map(function (Collection $categoryItems): array {
                $category = $categoryItems->first()->itemCategory;

                $groups = $categoryItems
                    ->groupBy(
                        fn (Item $item) => $item->item_class_id ?? 'other'
                    )
                    ->map(function (Collection $classItems): array {
                        $class = $classItems->first()->itemClass;

                        $hasTypes = $classItems->contains(
                            fn (Item $item) => $item->itemType !== null
                        );

                        $types = $classItems
                            ->groupBy(
                                fn (Item $item) => $item->item_type_id ?? 'other'
                            )
                            ->map(function (Collection $typeItems) use ($hasTypes): array {
                                $type = $typeItems->first()->itemType;

                                return [
                                    'label' => $hasTypes
                                        ? ($type?->label() ?? 'Інше')
                                        : null,
                                    'sort_order' => $type?->sort_order ?? PHP_INT_MAX,
                                    'items' => $typeItems->values(),
                                ];
                            })
                            ->sortBy('sort_order')
                            ->values();

                        return [
                            'label' => $class?->label() ?? 'Інше',
                            'sort_order' => $class?->sort_order ?? PHP_INT_MAX,
                            'types' => $types,
                        ];
                    })
                    ->sortBy('sort_order')
                    ->values();

                return [
                    'label' => $category?->label() ?? 'Інше',
                    'sort_order' => $category?->sort_order ?? PHP_INT_MAX,
                    'groups' => $groups,
                ];
            })
            ->sortBy('sort_order')
            ->values();

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
     * Display the specified resource.
     */
    public function card(string $slug)
    {
        $item = Item::where('slug', $slug)->firstOrFail();

        return view('items.card', compact('item'));
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
        return $item->itemClass?->label() ?? 'Інше';
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
