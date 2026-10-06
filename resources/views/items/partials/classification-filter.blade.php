<form method="GET" action="{{ route('items.index') }}" class="items-classification-filter">
    <label>
        Категорія
        <select name="item_category_id" onchange="this.form.elements['item_class_id'].value = ''; this.form.elements['item_type_id'].value = ''; this.form.submit()">
            <option value="">Усі категорії</option>
            @foreach($classification['categories'] as $categoryOption)
                <option value="{{ $categoryOption->id }}" @selected($classification['selected']['item_category_id'] === $categoryOption->id)>{{ $categoryOption->name }}</option>
            @endforeach
        </select>
    </label>
    <label>
        Клас
        <select name="item_class_id" @disabled(!$classification['selected']['item_category_id']) onchange="this.form.elements['item_type_id'].value = ''; this.form.submit()">
            <option value="">Усі класи</option>
            @foreach($classification['classes'] as $classOption)
                <option value="{{ $classOption->id }}" @selected($classification['selected']['item_class_id'] === $classOption->id)>{{ $classOption->name }}</option>
            @endforeach
        </select>
    </label>
    <label>
        Тип
        <select name="item_type_id" @disabled(!$classification['selected']['item_class_id']) onchange="this.form.submit()">
            <option value="">Усі типи</option>
            @foreach($classification['types'] as $typeOption)
                <option value="{{ $typeOption->id }}" @selected($classification['selected']['item_type_id'] === $typeOption->id)>{{ $typeOption->name }}</option>
            @endforeach
        </select>
    </label>
    <button type="submit">Застосувати</button>
    <a href="{{ route('items.index') }}">Скинути</a>
</form>
