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
                        value="{{ $class->value }}"
                        @selected($selectedClass === $class->value)
                    >
                        {{ $class->label() }}
                    </option>

                @endforeach

            </select>

        </form>


        @forelse($spells as $level => $items)

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
                            href="{{ route(
                                'spells.show',
                                array_filter([
                                    'slug' => $spell->slug,
                                    'class' => $selectedClass,
                                ])
                            ) }}"
                            class="spell-list__item"
                        >

                            <span class="spell-list__icon-box">

                                @if($spell->image)

                                    <img
                                        src="{{ asset('images/spells') . '/' . rawurlencode($spell->image) }}"
                                        alt=""
                                        class="spell-list__icon"
                                        loading="lazy"
                                    >

                                @endif

                            </span>


                            <span class="spell-list__name">
                                {{ $spell->name }}
                            </span>


                            <span class="spell-list__school">
                                {{ $spell->school->label() }}
                            </span>

                        </a>

                    @endforeach

                </div>

            </section>

        @empty

            <p>
                Заклять не знайдено.
            </p>

        @endforelse

    </main>

@endsection
