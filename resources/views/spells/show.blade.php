@extends('layouts.app')

@section('title', $spell->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/spell.css') }}">
@endpush

@section('content')

    <main class="spell-page">

        <nav class="spell-breadcrumbs" aria-label="Хлібні крихти">
            <a
                href="{{ route(
                    'spells.index',
                    array_filter([
                        'class' => request('class'),
                    ])
                ) }}"
            >
                Закляття
            </a>

            <span class="spell-breadcrumbs__separator">›</span>

            <span class="spell-breadcrumbs__current">
                {{ $spell->name }}
            </span>
        </nav>


        <article class="spell-card {{ $spell->school->cssClass() }}">

            <header class="spell-card__header">

                <div class="spell-card__ornament">
                    <span></span>
                </div>

                <div
                    @class([
                        'spell-card__heading',
                        'spell-card__heading--no-icon' => !$spell->image,
                    ])
                >

                    @if($spell->image)
                        <div class="spell-card__icon-box">
                            <img
                                src="{{ asset('images/spells') . '/' . rawurlencode($spell->image) }}"
                                alt="{{ $spell->name }}"
                                class="spell-card__icon"
                            >
                        </div>
                    @endif


                    <div class="spell-card__heading-text">

                        <h1>
                            {{ $spell->name }}
                        </h1>

                        <div class="spell-card__subtitle">
                            {{ $spell->level->label() }},
                            {{ mb_strtolower($spell->school->label()) }}

                            @if($spell->ritual)
                                (ритуал)
                            @endif
                        </div>

                    </div>

                </div>

            </header>


            <div class="spell-card__body">

                <div class="spell-card__properties">

                    <div class="spell-property">
                        <span class="spell-property__name">
                            Час виконання:
                        </span>

                        <span class="spell-property__value">
                            {{ $spell->casting_time }}
                        </span>
                    </div>


                    <div class="spell-property">
                        <span class="spell-property__name">
                            Дистанція:
                        </span>

                        <span class="spell-property__value">
                            {{ $spell->range }}
                        </span>
                    </div>


                    <div class="spell-property">
                        <span class="spell-property__name">
                            Компоненти:
                        </span>

                        <span class="spell-property__value">
                            {{ $spell->componentsLabel() }}

                            @if($spell->component_material && $spell->material)
                                <span class="spell-property__note">
                                    ({{ $spell->material }})
                                </span>
                            @endif
                        </span>

                    </div>


                    <div class="spell-property">
                        <span class="spell-property__name">
                            Тривалість:
                        </span>

                        <span class="spell-property__value">
                            {{ $spell->duration }}
                        </span>
                    </div>

                </div>


                <div class="spell-card__divider">
                    <span></span>
                </div>


                <div class="spell-card__description">
                    {!! $spell->description !!}
                </div>

            </div>


            <footer class="spell-card__footer">
                <span></span>
            </footer>

        </article>

    </main>

@endsection
