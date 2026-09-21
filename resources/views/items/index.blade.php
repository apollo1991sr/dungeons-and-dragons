@extends('layouts.app')

@section('title', 'Предмети')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items-index.css') }}">
@endpush

@section('content')

    <main class="items-index">

        <h1 class="items-index__title">
            Предмети
        </h1>

        @foreach($categories as $category)

            <section class="items-category">

                <h2 class="items-category__title">
                    {{ $category['label'] }}:
                </h2>


                <div class="items-groups">

                    @foreach($category['groups'] as $group)

                        <section class="items-group">

                            <h3 class="items-group__title">
                                {{ $group['label'] }}:
                            </h3>


                            @foreach($group['types'] as $type)

                                <div class="items-type">

                                    @if($type['label'])

                                        <h4 class="items-type__title">
                                            {{ $type['label'] }}:
                                        </h4>

                                    @endif


                                    <div class="items-list">

                                        @foreach($type['items'] as $item)

                                            <a
                                                href="{{ route('items.show', $item->slug) }}"
                                                class="items-list__item"
                                            >

                                                <span
                                                    class="
                                                        items-list__image
                                                        pi-theme-{{ $item->rarity?->value ?? 'common' }}
                                                    "
                                                >

                                                    @if($item->image)

                                                        <img
                                                            src="{{ asset('images/items') . '/' . rawurlencode($item->image) }}"
                                                            alt="{{ $item->name }}"
                                                            loading="lazy"
                                                        >

                                                    @endif

                                                </span>


                                                <span class="items-list__name">
                                                    {{ $item->name }}
                                                </span>

                                            </a>

                                        @endforeach

                                    </div>

                                </div>

                            @endforeach

                        </section>

                    @endforeach

                </div>

            </section>

        @endforeach

    </main>

@endsection
