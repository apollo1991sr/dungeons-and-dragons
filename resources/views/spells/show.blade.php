@extends('layouts.app')

@section('title', $spell['name'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/spell.css') }}">
@endpush

@section('content')

    <main class="spell-page">

        <article class="spell-card {{ $spell['schoolClass'] }}">

            <header class="spell-card__header">

                <div class="spell-card__ornament">
                    <span></span>
                </div>

                <h1>
                    {{ $spell['name'] }}
                </h1>

                <div class="spell-card__subtitle">

                    @if((int) $spell['level'] === 0)
                        Замовляння,
                    @else
                        {{ $spell['level'] }} рівень,
                    @endif

                    {{ mb_strtolower($spell['school']) }}

                    @if($spell['ritual'] ?? false)
                        (ритуал)
                    @endif

                </div>

            </header>


            <div class="spell-card__body">

                <div class="spell-card__properties">

                    <div class="spell-property">
                        <span class="spell-property__name">
                            Час виконання:
                        </span>

                        <span class="spell-property__value">
                            {{ $spell['castingTime'] }}
                        </span>
                    </div>


                    <div class="spell-property">
                        <span class="spell-property__name">
                            Дистанція:
                        </span>

                        <span class="spell-property__value">
                            {{ $spell['range'] }}
                        </span>
                    </div>


                    <div class="spell-property">

                        <span class="spell-property__name">
                            Компоненти:
                        </span>

                        <span class="spell-property__value">

                            @php
                                $components = [];

                                if ($spell['components']['verbal'] ?? false) {
                                    $components[] = 'С';
                                }

                                if ($spell['components']['somatic'] ?? false) {
                                    $components[] = 'Т';
                                }

                                if ($spell['components']['material'] ?? false) {
                                    $components[] = 'М';
                                }
                            @endphp

                            {{ implode(', ', $components) }}

                            @if($spell['components']['materialDescription'] ?? null)
                                <span class="spell-property__note">
                                    ({{ $spell['components']['materialDescription'] }})
                                </span>
                            @endif

                        </span>

                    </div>


                    <div class="spell-property">

                        <span class="spell-property__name">
                            Тривалість:
                        </span>

                        <span class="spell-property__value">
                            {{ $spell['duration'] }}
                        </span>

                    </div>

                </div>


                <div class="spell-card__divider">
                    <span></span>
                </div>


                <div class="spell-card__description">
                    {!! $spell['description'] !!}
                </div>

            </div>


            <footer class="spell-card__footer">
                <span></span>
            </footer>

        </article>

    </main>

@endsection
