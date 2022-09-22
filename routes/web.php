<?php

use App\Http\Controllers\BetController;
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

// landing page
Route::view('/', 'welcome')->name('home');

// about page
Route::view('about', 'about')->name('about');

// value bets page
Route::get('value-bets', function () {
    return view('value-bets');
})->name('value-bets');

Route::middleware(['auth'])->group(function () {
    // show all bets
    Route::get('bets', [BetController::class, 'index'])
        ->name('bets');

    // log a new bet
    Route::get('bets/create', [BetController::class, 'create'])
        ->name('new-bet');
    Route::post('bets', [BetController::class, 'store']);

    // edit a bet
    Route::get('bets/{bet}/edit', [BetController::class, 'edit'])
        ->name('edit-bet');
    Route::patch('bets/{bet}', [BetController::class, 'update']);

    // delete a bet
    Route::get('bets/{bet}/delete', [BetController::class, 'delete'])
        ->name('delete-bet');
    Route::delete('bets/{bet}', [BetController::class, 'destroy']);

    // view all bets stats
    Route::get('stats', [BetController::class, 'stats'])
        ->name('stats');
});

require __DIR__ . '/auth.php';
