<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ItemPrintController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $catalog = Item::query()->orderBy('name')->get(['id', 'name']);
        $validator = Validator::make($request->query(), [
            'rows' => ['sometimes', 'array', 'max:50'],
            'rows.*' => ['required', 'array:id,quantity'],
            'rows.*.id' => ['required', 'integer', 'min:1'],
            'rows.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'rows.array' => 'Некоректний список предметів.',
            'rows.max' => 'Можна додати до 50 позицій.',
            'rows.*.array' => 'Некоректна позиція.',
            'rows.*.id.*' => 'Оберіть предмет зі списку.',
            'rows.*.quantity.*' => 'Кількість має бути цілим числом від 1 до 100.',
        ]);

        $printErrors = $validator->errors();
        $rows = [];
        $cards = collect();

        if ($printErrors->isEmpty()) {
            $rows = array_values($validator->validated()['rows'] ?? []);
            if (array_sum(array_column($rows, 'quantity')) > 200) {
                $printErrors->add('rows', 'За один раз можна надрукувати до 200 карток.');
            } else {
                $selected = Item::query()->whereIn('id', array_column($rows, 'id'))->get()->keyBy('id');
                foreach ($rows as $row) {
                    $item = $selected->get((int) $row['id']);
                    if (!$item) {
                        $printErrors->add('rows', 'Один із предметів більше недоступний. Оберіть його знову.');
                        break;
                    }
                    for ($copy = 0; $copy < (int) $row['quantity']; $copy++) {
                        $cards->push($item);
                    }
                }
            }
        } else {
            // Preserve ordinary form values, but never send nested/untrusted shapes to Blade.
            $input = $request->query('rows', []);
            foreach (is_array($input) ? array_slice($input, 0, 50) : [] as $row) {
                if (!is_array($row)) continue;
                $rows[] = [
                    'id' => is_scalar($row['id'] ?? null) ? (string) $row['id'] : '',
                    'quantity' => is_scalar($row['quantity'] ?? null) ? (string) $row['quantity'] : 1,
                ];
            }
        }

        if ($printErrors->isNotEmpty()) $cards = collect();
        if ($rows === []) $rows = [['id' => '', 'quantity' => 1]];

        return response()->view('items.print', [
            'catalog' => $catalog,
            'rows' => $rows,
            'sheets' => $cards->chunk(4),
            'cardCount' => $cards->count(),
            'printErrors' => $printErrors,
        ], $printErrors->isEmpty() ? 200 : 422);
    }
}
