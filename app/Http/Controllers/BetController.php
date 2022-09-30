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
        // persist
        // redirect

        // $attributes = $request->validate([
        //     'match' => ['required', 'string'],
        //     'bet_size' => ['required'],
        //     'odds' => ['required'],
        // ]);

        // $attributes['user_id'] = auth()->id();

        Bet::create($request->all());
        
        // Bet::create($attributes);

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
