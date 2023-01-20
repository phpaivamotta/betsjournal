<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBetRequest;
use App\Http\Requests\UpdateBetRequest;
use App\Http\Resources\BetResource;
use App\Models\Bet;
use App\Services\ConvertOddsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Bets
 * 
 * Manage your bet resources.
 */
class BetController extends Controller
{
    /**
     * Display all bets.
     * 
     * Get all of your recorded bets.
     * 
     * @apiResourceCollection App\Http\Resources\BetResource
     * @apiResourceModel App\Models\Bet
     * @queryParam page int Page to view.
     *
     */
    public function index()
    {
        return BetResource::collection(Bet::where('user_id', auth()->id())->paginate(20));
    }

    /**
     * Store a newly created bet.
     *
     * @bodyParam match string required The name of the match. Example: Real Madrid vs Barcelona
     * @bodyParam bet_size float required The amount staked on the bet. Example: 100.0
     * @bodyParam odd float|int required The odd of the bet being offered. Example: 2.5
     * @bodyParam sport string required The name of the sport. Example: Soccer
     * @bodyParam match_date string required The date of the match. Example: 2023-1-25 
     * @bodyParam match_time string required The time of the match. Example: 21:00
     * @bodyParam bookie string required The name of bookie offering the bet. Example: bet365
     * @bodyParam bet_type string required The type of bet. Example: Money Line
     * @bodyParam bet_pick string required The name of the team being bet on. Example: Real Madrid
     * @bodyParam bet_description string Brief description of the bet being placed. Example: Champions League Final
     * @bodyParam result int The result of the bet being recorded, ranging from 0 to 2: Example: 1
     * @bodyParam cashout int|float The amount for which the bet was cashed out. Example: 50.0
     * @bodyParam categories[] int An array of categories for which the bet belogs to. Example:2
     * 
     * @apiResource 201 App\Http\Resources\BetResource
     * @apiResourceModel App\Models\Bet
     */
    public function store(StoreBetRequest $request)
    {
        $attributes = $request->validated();

        // get user prefence for odd type (american or decimal),
        // validate accordingly then store it into attributes array as respective odd_type value (american_odd) or (decimal_odd)
        // convert american to decimal or vice-versa depending on user odd_type preference
        $user_odd_type = auth()->user()->odd_type;

        if ($user_odd_type === 'american') {

            $attributes['american_odd'] = $attributes['odd'];

            $attributes['decimal_odd'] = ConvertOddsService::americanToDecimal($attributes['american_odd']);
        } elseif ($user_odd_type === 'decimal') {


            $attributes['decimal_odd'] = $attributes['odd'];

            $attributes['american_odd'] = ConvertOddsService::decimalToAmerican($attributes['decimal_odd']);
        }

        // remove odd field since it has already been transformed into american and decimal odds
        unset($attributes['odd']);

        // since the categories field is being validated in the Form Request, it should be unset
        // before the bet is created 
        if ($request->has('categories')) {
            $categories = $attributes['categories'];
            unset($attributes['categories']);
        }

        // get user id from auth instead of request
        $attributes['user_id'] = auth()->id();

        // persist
        $bet = Bet::create($attributes);

        // attach categories if any
        if (isset($categories)) {
            $bet->categories()->attach($categories);
        }

        return response(new BetResource(Bet::find($bet->id)), 201);
    }

    /**
     * Show a single, specific bet.
     * 
     * See the details for a specific bet.
     * 
     * @urlParam id int required The ID of the bet.
     * 
     * @apiResource App\Http\Resources\BetResource
     * @apiResourceModel App\Models\Bet
     */
    public function show(Bet $bet)
    {
        abort_if($bet->user_id !== auth()->id(), 403);

        return new BetResource(Bet::find($bet->id));
    }

    /**
     * Update a bet.
     * 
     * Modify a previously recorded bet.
     * 
     * @urlParam id int required The ID of the bet to be updated.
     * 
     * @bodyParam match string required The name of the match. Example: Real Madrid vs Barcelona
     * @bodyParam bet_size float required The amount staked on the bet. Example: 100.0
     * @bodyParam odd float|int required The odd of the bet being offered. Example: 2.5
     * @bodyParam sport string required The name of the sport. Example: Soccer
     * @bodyParam match_date string required The date of the match. Example: 2023-1-25 
     * @bodyParam match_time string required The time of the match. Example: 21:00
     * @bodyParam bookie string required The name of bookie offering the bet. Example: bet365
     * @bodyParam bet_type string required The type of bet. Example: Money Line
     * @bodyParam bet_pick string required The name of the team being bet on. Example: Real Madrid
     * @bodyParam bet_description string Brief description of the bet being placed. Example: Champions League Final
     * @bodyParam result int The result of the bet being recorded, ranging from 0 to 2: Example: 1
     * @bodyParam cashout int|float The amount for which the bet was cashed out. Example: 50.0
     * @bodyParam categories[] int An array of categories for which the bet belogs to. Example:2
     * 
     * @apiResource App\Http\Resources\BetResource
     * @apiResourceModel App\Models\Bet
     */
    public function update(UpdateBetRequest $request, Bet $bet)
    {
        $attributes = $request->validated();

        // get user prefence for odd type (american or decimal),
        // validate accordingly then store it into attributes array as respective odd_type value (american_odd) or (decimal_odd)
        // convert american to decimal or vice-versa depending on user odd_type preference
        $user_odd_type = auth()->user()->odd_type;

        if ($user_odd_type === 'american') {

            $attributes['american_odd'] = $attributes['odd'];

            $attributes['decimal_odd'] = ConvertOddsService::americanToDecimal($attributes['american_odd']);
        } elseif ($user_odd_type === 'decimal') {

            $attributes['decimal_odd'] = $attributes['odd'];

            $attributes['american_odd'] = ConvertOddsService::decimalToAmerican($attributes['decimal_odd']);
        }

        // remove odd field since it has already been transformed into american and decimal odds
        unset($attributes['odd']);

        // since the categories field is being validated in the Form Request, it should be unset
        // before the bet is created 
        if ($request->has('categories')) {
            $categories = $attributes['categories'];
            unset($attributes['categories']);
        }

        $bet->update($attributes);

        // sync categories
        // if no categories were passed in the request, then "erase" any category present by syncing an empty array
        if (isset($categories)) {
            $bet->categories()->sync($categories);
        } else {
            $bet->categories()->sync([]);
        }

        return new BetResource(Bet::find($bet->id));
    }

    /**
     * Delete bet.
     * 
     * @urlParam id int required The ID of the bet to be deleted.
     * 
     * @response {
     *     "message": "Bet deleted!"
     * }
     */
    public function destroy(Bet $bet)
    {
        abort_if($bet->user_id !== auth()->id(), 403);

        $bet->delete();

        return response()->json(['message' => 'Bet deleted!'], 200);
    }
}
