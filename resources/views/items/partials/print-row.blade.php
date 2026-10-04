<div class="item-print-row">
    <label>
        <span>Пошук предмета</span>
        <input type="search" class="item-print-search" placeholder="Введіть частину назви" autocomplete="off">
    </label>
    <label>
        <span>Предмет</span>
        <select data-field="id" name="rows[{{ $index }}][id]" required>
            <option value="">Оберіть предмет</option>
            @foreach($catalog as $option)
                <option value="{{ $option->id }}" @selected((string) $row['id'] === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
    </label>
    <label>
        <span>Кількість</span>
        <input data-field="quantity" type="number" name="rows[{{ $index }}][quantity]" value="{{ $row['quantity'] }}" min="1" max="100" step="1" required>
    </label>
    <button type="button" data-remove aria-label="Прибрати позицію">Прибрати</button>
</div>
