@extends('layouts.app')

@section('title', $item->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endpush

@section('content')

    <div class="main-container">

        <div class="resizable-container">
            <div class="page">

                <main class="page__main" lang="uk">

                    <nav class="breadcrumbs" aria-label="Хлібні крихти">
                        <a href="{{ route('home') }}">
                            Головна
                        </a>

                        <span class="spell-breadcrumbs__separator" aria-hidden="true">›</span>

                        <a href="{{ route('items.index') }}" class="breadcrumbs__link">
                            Предмети
                        </a>

                        <span class="breadcrumbs__separator" aria-hidden="true">›</span>

                        <span class="breadcrumbs__current" aria-current="page">
                            {{ $item->name }}
                        </span>
                    </nav>

                    <div class="page-content">

                        @php
                            $attributes = collect($item->attributes ?? []);

                            $inlineAfterOtherAttributes = $attributes->filter(function ($attribute) {
                                return ($attribute['placement'] ?? null) === 'after-other'
                                    && ($attribute['layout'] ?? null) === 'inline-row';
                            });

                            $regularAttributes = $attributes->reject(function ($attribute) {
                                return ($attribute['placement'] ?? null) === 'after-other'
                                    && ($attribute['layout'] ?? null) === 'inline-row';
                            });
                        @endphp

                        {{-- =========================================================
                             INFOBOX
                        ========================================================== --}}

                        <aside
                            role="region"
                            class="
                                portable-infobox
                                pi-background
                                pi-border-color
                                pi-theme-{{ $item->rarity?->value ?? 'common' }}
                                pi-layout-default
                            "
                        >

                            {{-- Назва --}}
                            <h2 class="pi-item pi-item-spacing pi-title pi-secondary-background">
                                <span class="bg-title">
                                    {{ $item->name }}
                                </span>
                            </h2>


                            {{-- Зображення --}}
                            @if($item->image)

                                <section class="pi-item pi-panel pi-border-color">

                                    <figure class="pi-item pi-image">

                                        <img
                                            src="{{ asset('images/items') . '/' . rawurlencode($item->image) }}"
                                            alt="{{ $item->name }}"
                                        >

                                    </figure>

                                </section>

                            @endif


                            {{-- =====================================================
                                 ЗАГАЛЬНА ІНФОРМАЦІЯ
                            ====================================================== --}}

                            <section class="pi-item pi-group pi-border-color">

                                <h2 class="pi-item pi-header pi-secondary-font pi-item-spacing pi-secondary-background">
                                    Загальна інформація
                                </h2>


                                {{-- Категорія --}}
                                @if($item->category)

                                    <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                        <h3 class="pi-data-label pi-secondary-font">
                                            Категорія
                                        </h3>

                                        <div class="pi-data-value pi-font">

                                            <span
                                                class="icon item-category {{ $item->category->icon() }}"
                                            ></span>

                                            <span>
                                                {{ $item->category->label() }}
                                            </span>

                                        </div>

                                    </div>

                                @endif


                                {{-- Клас --}}
                                @if($item->item_class)

                                    <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                        <h3 class="pi-data-label pi-secondary-font">
                                            Клас
                                        </h3>

                                        <div class="pi-data-value pi-font">

                                            <span
                                                class="icon item-class {{ $item->item_class->icon() }}"
                                            ></span>

                                            {{ $item->item_class->label() }}

                                        </div>

                                    </div>

                                @endif


                                {{-- Тип --}}
                                @if($item->type)

                                    <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                        <h3 class="pi-data-label pi-secondary-font">
                                            Тип
                                        </h3>

                                        <div class="pi-data-value pi-font">

                                            <span
                                                class="icon {{ $item->type->icon() }}"
                                            ></span>

                                            {{ $item->type->label() }}

                                        </div>

                                    </div>

                                @endif


                                {{-- Спеціалізація --}}
                                @if($item->proficiency)

                                    <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                        <h3 class="pi-data-label pi-secondary-font">
                                            Спеціалізація
                                        </h3>

                                        <div class="pi-data-value pi-font">

                                            <span class="icon proficiency"></span>

                                            {{ $item->proficiency }}

                                        </div>

                                    </div>

                                @endif


                                {{-- Рідкісність --}}
                                @if($item->rarity)

                                    <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                        <h3 class="pi-data-label pi-secondary-font">
                                            Рідкісність
                                        </h3>

                                        <div class="pi-data-value pi-font">

                                            <span class="icon rarity"></span>

                                            <span class="label">
                                                {{ $item->rarity->label() }}
                                            </span>

                                        </div>

                                    </div>

                                @endif

                            </section>


                            {{-- =====================================================
                                 СТАТИСТИКА
                            ====================================================== --}}

                            @if(!empty($item->stats))

                                <section class="pi-item pi-group pi-border-color">

                                    <h2 class="pi-item pi-header pi-secondary-font pi-item-spacing pi-secondary-background">
                                        Статистика
                                    </h2>

                                    @if(!empty($item->damage))
                                        @foreach($item->damage as $damage)
                                            <section class="pi-item pi-group pi-border-color">
                                                <table class="pi-horizontal-group{{ $loop->first ? '' : ' pi-horizontal-group-no-labels' }}">
                                                    @if($loop->first)
                                                        <thead>
                                                        <tr>
                                                            <th class="pi-horizontal-group-item pi-data-label pi-secondary-font pi-border-color pi-item-spacing">
                                                                Шкода
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                    @endif

                                                    <tbody>
                                                    <tr>
                                                        <td class="pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing pi-item-damage">
                                                            <span class="text-semibold inline-flex">{!! $damage['value'] !!}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </section>
                                        @endforeach
                                    @endif

                                    @foreach($item->stats as $stat)

                                        <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                            <h3 class="pi-data-label pi-secondary-font">
                                                {{ $stat['label'] }}
                                            </h3>

                                            <div class="pi-data-value pi-font">

                                                @if(!empty($stat['icon']))

                                                    <span
                                                        class="icon {{ $stat['icon'] }}"
                                                    ></span>

                                                @endif

                                                {!! $stat['value'] !!}

                                            </div>

                                        </div>

                                    @endforeach

                                </section>

                            @endif


                            {{-- =====================================================
                                 ДОДАТКОВІ ХАРАКТЕРИСТИКИ
                            ====================================================== --}}

                            @if($regularAttributes->isNotEmpty())

                                <section class="pi-item pi-group pi-border-color">

                                    @if(
                                        $regularAttributes->contains(fn ($attribute) => !empty($attribute['group']))
                                    )

                                        @php
                                            $groups = $regularAttributes->groupBy(fn ($attribute) => $attribute['group'] ?? '');
                                        @endphp

                                        @foreach($groups as $group => $groupAttributes)

                                            @if($group)
                                                <h2 class="pi-item pi-header pi-secondary-font pi-item-spacing pi-secondary-background">
                                                    {{ $group }}
                                                </h2>
                                            @endif

                                            @foreach($groupAttributes as $attribute)

                                                <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                                    <h3 class="pi-data-label pi-secondary-font">
                                                        {{ $attribute['label'] }}
                                                    </h3>

                                                    <div class="pi-data-value pi-font">

                                                        @if(!empty($attribute['icon']))
                                                            <span class="icon {{ $attribute['icon'] }}"></span>
                                                        @endif

                                                        {!! $attribute['value'] ?? '' !!}

                                                    </div>

                                                </div>

                                            @endforeach

                                        @endforeach

                                    @else

                                        @foreach($regularAttributes as $attribute)

                                            <div class="pi-item pi-data pi-item-spacing pi-border-color">

                                                <h3 class="pi-data-label pi-secondary-font">
                                                    {{ $attribute['label'] }}
                                                </h3>

                                                <div class="pi-data-value pi-font">

                                                    @if(!empty($attribute['icon']))
                                                        <span class="icon {{ $attribute['icon'] }}"></span>
                                                    @endif

                                                    {!! $attribute['value'] ?? '' !!}

                                                </div>

                                            </div>

                                        @endforeach

                                    @endif

                                </section>

                            @endif


                            {{-- =====================================================
                                 ЦІНА / ВАГА
                            ====================================================== --}}

                            @if($item->price !== null || $item->weight !== null)

                                <section class="pi-item pi-group pi-border-color">

                                    <h2 class="pi-item pi-header pi-secondary-font pi-item-spacing pi-secondary-background">
                                        Інше
                                    </h2>

                                    <section class="pi-item pi-group pi-border-color">

                                        <table class="pi-horizontal-group">

                                            <thead>
                                            <tr>

                                                @if($item->price !== null)

                                                    <th class="pi-horizontal-group-item pi-data-label pi-secondary-font pi-border-color pi-item-spacing">
                                                        Ціна
                                                    </th>

                                                @endif

                                                @if($item->weight !== null)

                                                    <th class="pi-horizontal-group-item pi-data-label pi-secondary-font pi-border-color pi-item-spacing">
                                                        Вага
                                                    </th>

                                                @endif

                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>

                                                @if($item->price !== null)

                                                    <td class="pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing">

                                                        <span class="icon price"></span>

                                                        {{ $item->price + 0 }}

                                                    </td>

                                                @endif

                                                @if($item->weight !== null)

                                                    <td class="pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing">

                                                        <span class="icon weight"></span>

                                                        {{ $item->weight + 0 }} кг

                                                    </td>

                                                @endif

                                            </tr>
                                            </tbody>

                                        </table>

                                    </section>

                                    @if($inlineAfterOtherAttributes->isNotEmpty())

                                        <section class="pi-item pi-group pi-border-color">

                                            <table class="pi-horizontal-group pi-horizontal-group-no-labels">
                                                <tbody>
                                                @foreach($inlineAfterOtherAttributes as $attribute)
                                                    <tr>
                                                        <td class="pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing">
                                                            {!! $attribute['html'] ?? '' !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </section>

                                    @endif

                                </section>

                            @endif

                            @if($item->action || $item->single_use)
                                <section class="pi-item pi-group pi-border-color">
                                    <table class="pi-horizontal-group pi-horizontal-group-no-labels">
                                        <tbody>
                                        <tr>
                                            <td class="pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing">
                                                @if($item->action)
                                                    <span class="icon {{ $item->action->icon() }}"></span>&nbsp;<span class="link">{{ $item->action->label() }}</span>
                                                @endif

                                                @if($item->single_use)
                                                    <span class="icon single-use"></span>&nbsp;<span class="accent">Одноразове</span>
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </section>
                            @endif


                            {{-- =====================================================
                                 FOOTER INFOBOX
                            ====================================================== --}}

                            @if($item->category)

                                <nav class="pi-navigation pi-item-spacing pi-secondary-font">

                                    <span class="link">
                                        {{ $item->category->label() }}
                                    </span>

                                </nav>

                            @endif

                        </aside>

                        {{-- =========================================================
                             ОСНОВНИЙ КОНТЕНТ
                        ========================================================== --}}

                        <h1>
                            {{ $item->name }}
                        </h1>

                        @if($item->description)
                            <div class="description">
                                <div class="icon"></div>

                                <div class="text">
                                    {!! $item->description !!}
                                </div>
                            </div>
                        @endif

                        @foreach($item->content ?? [] as $block)
                            @if(!empty($block['title']))
                                <h2>{{ $block['title'] }}</h2>
                            @endif

                            {!! $block['html'] ?? '' !!}
                        @endforeach
                    </div>
                </main>

            </div>
        </div>
    </div>

@endsection
