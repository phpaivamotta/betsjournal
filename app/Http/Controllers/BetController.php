<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BetController extends Controller
{
    public function index()
    {
        return view('user-bets.index');
    }

    public function stats()
    {
        return view('user-bets.stats');
    }

    public function create()
    {
        return view('user-bets.create');
    }

    public function store()
    {
        
    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function destroy()
    {

    }
}
