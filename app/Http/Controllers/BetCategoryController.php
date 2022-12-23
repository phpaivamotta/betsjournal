<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\Category;
use Illuminate\Http\Request;

class BetCategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories;

        return view('bets.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required'],
            'color' => ['required']
        ]);

        auth()->user()->categories()->create($attributes);

        return back();
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->all());

        return back();
    }
}
