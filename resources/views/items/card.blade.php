@extends('layouts.app')

@section('title', $item->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/item-card.css') }}">
@endpush

@section('content')
    <div class="item-card-page" lang="uk">
        <nav class="item-card-toolbar" aria-label="Навігація">
            <a href="{{ route('items.index') }}">← Предмети</a>
            <a href="{{ route('items.show', $item->slug) }}">Звичайна версія</a>
            <a href="{{ route('items.print') }}">Друк кількох карток</a>
            <span>Для друку натисніть Ctrl+P</span>
        </nav>

        @include('items.partials.card', ['item' => $item])
    </div>
@endsection
