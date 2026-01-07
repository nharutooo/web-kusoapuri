<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/hayaoshi', 'hayaoshi')->name('hayaoshi');
Route::view('/hayaoshi/tutorial', 'hayaoshi.play', ['initialTutorial' => true])->name('hayaoshi.tutorial');
Route::view('/hayaoshi/main', 'hayaoshi.play', ['initialTutorial' => false])->name('hayaoshi.main');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
