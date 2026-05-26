<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetController;
use Illuminate\Support\Facades\Route;

// ── Public routes ──────────────────────────────────────────────────────
Route::get('/',              [SetController::class, 'home'])->name('home');
Route::get('/encyclopedia',  [SetController::class, 'encyclopedia'])->name('encyclopedia');
Route::get('/set/{setId}',   [SetController::class, 'show'])->name('set.show');
Route::get('/open',          [SetController::class, 'openIndex'])->name('open.index');
Route::get('/open/{setId}',  [SetController::class, 'openBooster'])->name('open.booster');
Route::get('/friends',       fn() => view('friends'))->name('friends');
Route::get('/trades',        fn() => view('trades'))->name('trades');

// ── Auth routes (Breeze) ───────────────────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
