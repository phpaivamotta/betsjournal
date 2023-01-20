<?php

use App\Http\Controllers\Api\V1\BetController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\StatsController;
use App\Http\Controllers\Api\V1\ValueBets;
use App\Models\Category;
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

Route::middleware('auth:sanctum')->prefix('v1')->group( function () {
    /**
     * @group User
     * 
     * Get your account information.
     * 
     * @response {
	 *  "id": 1,
	 *  "name": "Pedro Motta",
	 *  "email": "phpaivamotta@gmail.com",
	 *  "email_verified_at": null,
	 *  "odd_type": "decimal",
	 *  "created_at": "2022-12-30T01:07:15.000000Z",
	 *  "updated_at": "2023-01-09T03:38:15.000000Z"
     * }
     */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Bet stats
    Route::get('bets/stats', StatsController::class);

    // Bet resources
    Route::apiResource('bets', BetController::class);

    /**
     * @group Category
     * 
     * returns list of available category colors.
     * 
     * @response [
	 *     "blue",
	 *     "indigo",
	 *     "brown",
	 *     "black",
	 *     "yellow"
     * ]
     */
    Route::get('categories/colors', function () {
        return response()->json(Category::COLORS, 200);
    });

    // Category resources
    Route::apiResource('categories', CategoryController::class);

    // value bets
    Route::get('value-bets', ValueBets::class);
});
