<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Games\JankenController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/hayaoshi', 'games.Hayaoshi.index')->name('hayaoshi');
Route::view('/hayaoshi/tutorial', 'games.Hayaoshi.play', ['initialTutorial' => true])->name('hayaoshi.tutorial');
Route::view('/hayaoshi/main', 'games.Hayaoshi.play', ['initialTutorial' => false])->name('hayaoshi.main');
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
    Route::get('/games/hayaoshi', function () { return "早押しゲーム（準備中）"; })->name('games.hayaoshi');
    
    // 蛇
    Route::get('/games/hebi', function () { return "ヘビゲーム（準備中）"; })->name('games.hebi');
    
    // 神経衰弱
    Route::get('/games/shinkei', function () { return "神経衰弱（準備中）"; })->name('games.shinkei');
});

require __DIR__.'/auth.php';
