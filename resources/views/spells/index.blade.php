@extends('layouts.app')

@section('title', 'Закляття')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/spells.css') }}">
@endpush

@section('content')

    <main class="spells-page">

        <h1>Закляття</h1>

        <form
            method="GET"
            action="{{ route('spells.index') }}"
            class="spells-filter"
        >

            <label for="class">
                Клас:
            </label>

            <select
                name="class"
                id="class"
                onchange="this.form.submit()"
            >

                <option value="">
                    Усі класи
                </option>

                @foreach($classes as $class)

                    <option
                        value="{{ $class }}"
                        @selected($selectedClass === $class)
                    >
                        {{ $class }}
                    </option>

                @endforeach

            </select>

        </form>

        @foreach($spells as $level => $items)

            <section class="spell-level">

                <h2 class="spell-level__title">

                    @if((int) $level === 0)
                        Замовляння
                    @else
                        {{ $level }} рівень
                    @endif

                </h2>

                <div class="spell-list">

                    @foreach($items as $spell)

                        <a
                            href="{{ route('spells.show', $spell['slug']) }}"
                            class="spell-list__item"
                        >

                            <span class="spell-list__name">
                                {{ $spell['name'] }}
                            </span>

                            <span class="spell-list__school">
                                {{ $spell['school'] }}
                            </span>

                        </a>

                    @endforeach

                </div>

            </section>

        @endforeach

    </main>

@endsection
