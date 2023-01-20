<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BetCategory extends Component
{
    public $name;
    public $color = 'blue';
    public $colors = Category::COLORS;
    public Category $currentCategory;
    public $showDeleteModal = false;
    public $showUpdateModal = false;
    
    public function mount()
    {
        $this->currentCategory = new Category();
    }

    public function render()
    {
        return view('livewire.bet-category', [
            'categories' => Category::where('user_id', auth()->id())->get()
        ]);
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'max:20', Rule::notIn(auth()->user()->categories->pluck('name')->toArray())],
            'color' => ['required', Rule::in(Category::COLORS)]
        ];
    }

    protected $messages = [
        'name.not_in' => 'This category already exists.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function create()
    {
        if (auth()->user()->categories->count() >= 10) {
            return session()->flash('warning', 'Cannot add more than 10 categories.');
        }

        $this->validate();

        Category::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'color' => $this->color
        ]);

        $this->resetAttributes();
    }

    public function confirmDelete(Category $category)
    {
        $this->currentCategory = $category;

        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        $this->currentCategory->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Category deleted!');
    }

    public function selectCategoryToEdit(Category $category)
    {
        $this->currentCategory = $category;

        $this->name = $this->currentCategory->name;
        $this->color = $this->currentCategory->color;

        $this->showUpdateModal = true;
    }

    public function updateCategory()
    {
        // remove category being edited from list of user's categories to avoid not being able to just edit the color of a category
        $current = $this->currentCategory->getKey();
        $categories = auth()->user()->categories->except($current);

        $this->validate([
            'name' => ['required', 'max:20', Rule::notIn($categories->pluck('name')->toArray())],
            'color' => ['required', Rule::in(Category::COLORS)]
        ]);

        $this->currentCategory->update([
            'name' => $this->name,
            'color' => $this->color
        ]);

        $this->resetAttributes();

        session()->flash('success', 'Category updated!');
    }

    public function resetAttributes()
    {
        $this->name = '';
        $this->color = 'blue';
        $this->showUpdateModal = false;
    }
}
