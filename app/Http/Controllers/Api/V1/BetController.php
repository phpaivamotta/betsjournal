<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBetRequest;
use App\Http\Requests\UpdateBetRequest;
use App\Http\Resources\BetResource;
use App\Models\Bet;
use App\Services\ConvertOddsService;

class BetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BetResource::collection(Bet::where('user_id', auth()->id())->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreBetRequest  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  \App\Models\Bet  $bet
     * @return \Illuminate\Http\Response
     */
    public function show(Bet $bet)
    {
        abort_if($bet->user_id !== auth()->id(), 403);

        return new BetResource(Bet::find($bet->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\UpdateBetRequest  $request
     * @param  \App\Models\Bet  $bet
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bet  $bet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bet $bet)
    {
        abort_if($bet->user_id !== auth()->id(), 403);

        $bet->delete();

        return response()->json('Bet deleted!', 204);
    }
}
