<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\User;
use Illuminate\Http\Request;

class BetController extends Controller
{
    public function index()
    {
        return view('bets.index', [
            'bets' => Bet::where('user_id', '=', auth()->id())->latest()->get(),
            'optional_attributes' => Bet::$optional_attributes
        ]);
    }

    public function create()
    {
        return view('bets.create');
    }

    public function store(Request $request)
    {
        // validate
        $attributes = $request->validate([
            'match' => ['required', 'string', 'max:255'],
            'bet_size' => ['required', 'numeric', 'min:0'],

            'sport' => ['nullable', 'string', 'max:255'],
            'match_date' => ['nullable', 'date'],
            'match_time' => ['nullable', 'date_format:H:i'],
            'bookie' => ['nullable', 'string', 'max:255'],
            'bet_type' => ['nullable', 'string', 'max:255'],
            'bet_description' => ['nullable', 'string', 'max:255'],
            'bet_pick' => ['nullable', 'string', 'max:255'],
            'result' => ['nullable', 'boolean']
        ]);

        // get user prefence for odd type (american or decimal),
        // validate accordingly then store it into attributes array as respective odd_type value (american_odd) or (decimal_odd)
        // convert american to decimal or vice-versa depending on user odd_type preference
        $user_odd_type = auth()->user()->odd_type;

        if ($user_odd_type === 'american') {

            // validate method returns an array ['odd' => $odd]
            $american_odd_array = $request->validate([
                'odd' => ['required', 'numeric']
            ]);

            // so it is unpacked here
            $attributes['american_odd'] = $american_odd_array['odd'];

            $attributes['decimal_odd'] = self::americanToDecimal($attributes['american_odd']);

        } elseif ($user_odd_type === 'decimal') {

            // validate method returns an array ['odd' => $odd]
            $decimal_odd_array = $request->validate([
                'odd' => ['required', 'numeric', 'min:1']
            ]);

            // so it is unpacked here
            $attributes['decimal_odd'] = $decimal_odd_array['odd'];

            $attributes['american_odd'] = self::decimalToAmerican($attributes['decimal_odd']);
        }

        // get user id from auth instead of request
        $attributes['user_id'] = auth()->id();

        // persist
        Bet::create($attributes);

        // redirect
        return redirect('/bets');
    }


    /**
     * @param int|float $odd
     * 
     * @return int|float
     */
    public static function americanToDecimal($odd)
    {
        if ($odd > 0) {
            return ($odd / 100) + 1;
        } else {
            return (100 / abs($odd)) + 1;
        }
    }

    /**
     * @param int|float $odd
     * 
     * @return int|float
     */
    private static function decimalToAmerican($odd)
    {
        if ($odd >= 2) {
            return ($odd - 1) * 100;
        } else {
            return -100 / ($odd - 1);
        }
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }

    public function stats()
    {
        return view('bets.stats');
    }
}
