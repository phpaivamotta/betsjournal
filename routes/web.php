<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('bets', [BetController::class, 'index'])->name('bets');

    Route::get('new-bet', [BetController::class, 'create'])->name('new-bet');

    Route::get('stats', [BetController::class, 'stats'])->name('stats');
});

require __DIR__ . '/auth.php';
