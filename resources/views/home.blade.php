@extends('layouts.app')

@section('title', 'D&D')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

    <main class="home-page">

        <h1>Dungeons & Dragons</h1>

        <div class="home-links">

            <a
                href="{{ route('items.index') }}"
                class="home-card"
            >
                <span class="home-card__title">
                    Предмети
                </span>

                <span class="home-card__text">
                    Зброя, обладунки, зілля та інше спорядження
                </span>
            </a>

            <a
                href="{{ route('spells.index') }}"
                class="home-card"
            >
                <span class="home-card__title">
                    Закляття
                </span>

                <span class="home-card__text">
                    Закляття за рівнями, школами та класами
                </span>
            </a>

        </div>

    </main>

@endsection
