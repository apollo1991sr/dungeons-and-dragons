<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use RuntimeException;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/items.json');

        if (! is_file($path)) {
            throw new RuntimeException("Items seed data not found: {$path}");
        }

        $items = json_decode(
            file_get_contents($path),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
}
