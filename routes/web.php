<?php

use App\Http\Controllers\BetController;
use App\Http\Livewire\ManageProfile;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SubscriberController;
use App\Http\Livewire\Api\ApiTokens;
use App\Http\Livewire\BetCategory;
use App\Http\Livewire\BetIndex;
use App\Http\Livewire\ValueBets;
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

// mailable
Route::get('/mail', function () {
    $matches = json_decode(file_get_contents(__DIR__.'/../json-responses/value-bets.json'), true);

    return new App\Mail\ValueBetsMail($matches);
});

// routes for all users
// landing page
Route::view('/', 'welcome')
    ->name('home');

// about page
Route::view('about', 'about')
    ->name('about');

// betting tools
// odds comparison page
Route::view('odds-comparison', 'betting-tools/odds-comparison')
    ->name('odds-comparison');

// Livewire valuebets
Route::get('value-bets', ValueBets::class)
    ->name('value-bets');

// odd converter page
Route::view('odd-converter', 'betting-tools/odd-converter')
    ->name('odd-converter');

// payout calculator
Route::view('payout-calculator', 'betting-tools/payout-calculator')
    ->name('payout-calculator');

// margin calculator
Route::view('margin-calculator', 'betting-tools/margin-calculator')
    ->name('margin-calculator');

// subscribe
Route::post('subscribers', [SubscriberController::class, 'store'])
    ->name('subscribers.store');

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
    Route::get('stats', StatsController::class)
        ->name('bets.stats');

    // Livewire categories 
    Route::get('categories', BetCategory::class)
        ->name('bets.categories');

    // record value bet
    Route::view('value-bets/record', 'bets.value-bets.record')
        ->name('value-bets.record');

    // manage API tokens 
    Route::get('api-tokens', ApiTokens::class)
        ->name('api-tokens');

    // manage profile user (Livewire)
    Route::get('profile', ManageProfile::class)
        ->name('manage-profile');
});

require __DIR__ . '/auth.php';
