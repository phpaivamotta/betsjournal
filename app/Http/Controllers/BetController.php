<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use Illuminate\Http\Request;

class BetController extends Controller
{
    public function index()
    {
        return view('bets.index', [
            'bets' => Bet::all()
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
            'odds' => ['required', 'numeric'],

            'sport' => ['nullable', 'string', 'max:255'],
            'match_date' => ['nullable', 'date'],
            'match_time' => ['nullable', 'date_format:H:i:s'],
            'bookie' => ['nullable', 'string', 'max:255'],
            'bet_type' => ['nullable', 'string', 'max:255'],
            'bet_description' => ['nullable', 'string', 'max:255'],
            'bet_pick' => ['nullable', 'string', 'max:255'],
            'result' => ['nullable', 'boolean']
        ]);

        // get user id from auth instead of request
        $attributes['user_id'] = auth()->id();

        // persist
        Bet::create($attributes);

        // redirect
        return redirect('/bets');
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
