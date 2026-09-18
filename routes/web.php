<?php

use App\Http\Controllers\SpellController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('spells.index');
});

Route::get('/spells', [SpellController::class, 'index'])
    ->name('spells.index');

Route::get('/spells/{slug}', [SpellController::class, 'show'])
    ->name('spells.show');
