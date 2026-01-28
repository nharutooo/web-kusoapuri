<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\games\HebiControllers;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🎲 Snake & Ladder Game
    Route::get('/games/snake-ladder', [HebiControllers::class, 'index'])->name('game.index');
    Route::post('/games/snake-ladder/roll', [HebiControllers::class, 'roll'])->name('game.roll');
    Route::post('/games/snake-ladder/reset', [HebiControllers::class, 'reset'])->name('game.reset');
});

Route::get('/snake', function () {
    return view('games.hebi.snake');
});

require __DIR__.'/auth.php';
