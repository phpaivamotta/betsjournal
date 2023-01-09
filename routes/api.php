<?php

use App\Http\Controllers\Api\V1\BetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // get auth user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Bet model
    Route::apiResource('bets', BetController::class)->middleware('auth:sanctum');
});
