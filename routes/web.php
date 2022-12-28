<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\BetCategory;
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

// betting tools
// odds comparison page
Route::view('odds-comparison', 'betting-tools/odds-comparison')->name('odds-comparison');

// world cup page
Route::view('world-cup', 'betting-tools/world-cup')->name('world-cup');

// odd converter page
Route::view('odd-converter', 'betting-tools/odd-converter')->name('odd-converter');

// payout calculator
Route::view('payout-calculator', 'betting-tools/payout-calculator')->name('payout-calculator');

// margin calculator
Route::view('margin-calculator', 'betting-tools/margin-calculator')->name('margin-calculator');

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
    Route::get('stats', [BetController::class, 'stats'])
        ->name('bets.stats');

    // Livewire categories 
    Route::get('categories', BetCategory::class)
        ->name('bets.categories');

    // edit profile
    Route::get('edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');
    Route::patch('update-profile', [ProfileController::class, 'update'])->name('update-profile');
});

require __DIR__ . '/auth.php';
