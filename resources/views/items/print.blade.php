@extends('layouts.app')

@section('title', 'Друк предметів')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/item-card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items-print.css') }}">
@endpush

@section('content')
<div class="item-print-page" lang="uk">
    <section class="item-print-controls" aria-label="Налаштування друку">
        <a href="{{ route('items.index') }}">← Предмети</a>
        <h1>Друк предметів</h1>
        <p>Картки 95 × 138,5 мм. Чотири на аркуші А4.</p>

        @if($printErrors->isNotEmpty())
            <div class="item-print-errors" role="alert">
                @foreach($printErrors->all() as $message)<p>{{ $message }}</p>@endforeach
            </div>
        @endif

        <form id="item-print-form" action="{{ route('items.print') }}" method="GET">
            <div id="item-print-rows">
                @foreach($rows as $index => $row)
                    @include('items.partials.print-row', compact('index', 'row', 'catalog'))
                @endforeach
            </div>
            <div class="item-print-actions">
                <button type="button" id="item-print-add">Додати предмет</button>
                <button type="submit">Сформувати картки</button>
                <button type="button" id="item-print-button" @disabled(!$cardCount)>Друкувати</button>
                <a href="{{ route('items.print') }}">Очистити</a>
            </div>
        </form>
        <template id="item-print-row-template">
            @include('items.partials.print-row', ['index' => '__INDEX__', 'row' => ['id' => '', 'quantity' => 1], 'catalog' => $catalog])
        </template>
        <p id="item-print-status" role="status" aria-live="polite">Карток: {{ $cardCount }}. Аркушів: {{ $sheets->count() }}.</p>
        <p>У вікні друку: А4, книжкова орієнтація, масштаб 100%, колонтитули вимкнено. Для кольорового тла увімкніть фонову графіку.</p>
        <div id="item-print-warnings" class="item-print-errors" role="status" hidden></div>
    </section>

    <div class="item-print-preview">
        <div class="item-print-sheets">
            @foreach($sheets as $sheet)
                <section class="item-print-sheet" aria-label="Аркуш {{ $loop->iteration }}">
                    @foreach($sheet as $item)
                        <div class="item-print-slot" data-name="{{ $item->name }}">
                            <div class="item-print-scaled">
                                @include('items.partials.card', ['item' => $item])
                            </div>
                        </div>
                    @endforeach
                </section>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/items-print.js') }}" defer></script>
@endpush
