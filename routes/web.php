<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Games\HayaoshiController;
use App\Http\Controllers\Games\ShinkeiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Games\JankenController;

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
    Route::get('/password-manager', [App\Http\Controllers\ExposedPasswordController::class, 'index'])->name('password.index');
    Route::post('/password-manager', [App\Http\Controllers\ExposedPasswordController::class, 'store'])->name('password.store');

    // じゃんけん
    Route::get('/games/janken', [JankenController::class, 'index'])->name('games.janken');

    // 早押し
    Route::get('/games/hayaoshi', [HayaoshiController::class, 'index'])->name('games.hayaoshi');
    Route::get('/games/hayaoshi/tutorial', [HayaoshiController::class, 'tutorial'])->name('games.hayaoshi.tutorial');
    Route::get('/games/hayaoshi/main', [HayaoshiController::class, 'main'])->name('games.hayaoshi.main');

    // 蛇
    Route::get('/games/hebi', function () { return "ヘビゲーム（準備中）"; })->name('games.hebi');

    // 神経衰弱
    Route::get('/games/shinkei', [ShinkeiController::class, 'index'])->name('games.shinkei');
});

require __DIR__.'/auth.php';
