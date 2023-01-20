<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Category
 * 
 * Manage your category resources.
 */
class CategoryController extends Controller
{
    /**
     * Display all categories.
     * 
     * Get all of your created categories.
     * 
     * @apiResourceCollection App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category
     *
     */
    public function index()
    {
        return CategoryResource::collection(Category::where('user_id', auth()->id())->get());
    }

    /**
     * Store a newly created category.
     *
     * @bodyParam name string required The name of the category. Example: Value Bets
     * @bodyParam color string required The category's color. Example: indigo
     * 
     * @apiResource 201 App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category
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
     * Show a single, specific category.
     * 
     * See the details for a specific category.
     * 
     * @urlParam id int required The ID of the category.
     * 
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category
     */
    public function show(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);

        return new CategoryResource(Category::find($category->id));
    }

    /**
     * Update a bet.
     * 
     * Modify a previously created category.
     * 
     * @urlParam id int required The ID of the category to be updated.
     * 
     * @bodyParam name string required The name of the category. Example: MMA
     * @bodyParam color string required The category's color. Example: blue
     * 
     * @apiResource App\Http\Resources\CategoryResource
     * @apiResourceModel App\Models\Category
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
     * Delete category.
     * 
     * @urlParam id int required The ID of the category to be deleted.
     * 
     * @response {
     *     "message": "Category deleted!"
     * }
     */
    public function destroy(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        
        $category->delete();

        return response()->json('Category deleted!', 200);
    }
}
