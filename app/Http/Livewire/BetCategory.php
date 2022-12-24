<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class BetCategory extends Component
{
    public $name;
    public $color = 'blue';
    public $colors = Category::COLORS;
    public Category $currentCategory;
    public $showDeleteModal = false;
    
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
            'name' => ['required', 'max:20', 'not_in:' . auth()->user()->categories->pluck('name')],
            'color' => ['required']
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

        $this->name = '';
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
}
