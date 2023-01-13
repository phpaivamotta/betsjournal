<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(Category::where('user_id', auth()->id())->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->categories->count() >= 10) {
            abort(409, 'Cannot add more than 10 categories.');
        }

        $request->validate([
            'name' => ['required', 'max:20', Rule::notIn(auth()->user()->categories->pluck('name')->toArray())],
            'color' => ['required', Rule::in(Category::COLORS)]
        ]);

        $category = Category::create([
            'user_id' => auth()->id(),
            'name' => $request['name'],
            'color' => $request['color']
        ]);

        return response(new CategoryResource(Category::find($category->id)), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);

        return new CategoryResource(Category::find($category->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);

        // remove category being edited from list of user's categories to avoid not being able to just edit the color of a category
        $current = $category->getKey();
        $categories = auth()->user()->categories->except($current);

        $request->validate([
            'name' => ['required', 'max:20', Rule::notIn($categories->pluck('name')->toArray())],
            'color' => ['required', Rule::in(Category::COLORS)]
        ]);

        $category->update([
            'user_id' => auth()->id(),
            'name' => $request['name'],
            'color' => $request['color']
        ]);

        return new CategoryResource(Category::find($category->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        
        $category->delete();

        return response()->json('Category deleted!', 204);
    }
}
