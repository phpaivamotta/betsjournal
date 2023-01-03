<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBetRequest;
use App\Http\Requests\UpdateBetRequest;
use App\Models\Bet;
use App\Services\ConvertOddsService;

class BetController extends Controller
{
    public function create()
    {
        return view('bets.create');
    }

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

        // get user id from auth instead of request
        $attributes['user_id'] = auth()->id();

        // persist
        $bet = Bet::create($attributes);

        // attach categories if any
        $bet->categories()->attach($request['categories']);

        // redirect
        return redirect('/bets')->with('success', "You've created a new bet!");
    }

    public function edit(Bet $bet)
    {
        return view('bets.edit', ['bet' => $bet]);
    }

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

        $bet->update($attributes);

        // sync categories
        $bet->categories()->sync($request['categories']);

        return redirect()
            ->route('bets.index', ['page' => request('page')])
            ->with('success', "Bet updated!");
    }
}
