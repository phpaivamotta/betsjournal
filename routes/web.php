<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\BetIndex;
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

// routes for all users
// landing page
Route::view('/', 'welcome')->name('home');

// about page
Route::view('about', 'about')->name('about');

// value bets page
Route::view('odds-comparison', 'odds-comparison')->name('odds-comparison');

// odd converter page
Route::view('odd-converter', 'odd-converter')->name('odd-converter');

// routes for registered users
Route::middleware(['auth'])->group(function () {
    // index all bets (LIVEWIRE!!)
    Route::get('bets', BetIndex::class)
        ->name('bets.index');

    // create a new bet
    Route::get('bets/create', [BetController::class, 'create'])
        ->name('bets.create');

    Route::post('bets', [BetController::class, 'store'])
        ->name('bets.store');

    // edit a bet
    Route::get('bets/{bet}/edit', [BetController::class, 'edit'])
        ->name('bets.edit');

    Route::patch('bets/{bet}', [BetController::class, 'update'])
        ->name('bets.update');

    // delete a bet
    Route::delete('bets/{bet}', [BetController::class, 'destroy'])
        ->name('bets.destroy');

    // view all bets stats
    // THIS ACTION SHOULD PROBABLY BE CHANGED
    Route::get('stats', [BetController::class, 'stats'])
        ->name('bets.stats');

    // edit profile
    Route::get('edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');
    Route::patch('update-profile', [ProfileController::class, 'update'])->name('update-profile');
});

require __DIR__ . '/auth.php';
