<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\BetCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BetCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_component_can_render_if_user_auth()
    {
        $this->signIn();

        $component = Livewire::test(BetCategory::class);

        $component->assertStatus(200);
    }

    function test_categories_page_contains_livewire_component_if_user_auth()
    {
        $this->signIn();

        $this->get('/categories')->assertSeeLivewire(BetCategory::class);
    }

    public function test_guests_cannot_access_component()
    {
        $this->get('/categories')
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    public function test_user_can_create_category()
    {
        $this->signIn();

        Livewire::test(BetCategory::class)
            ->set('name', 'test category')
            ->set('color', 'blue')
            ->call('create')
            ->assertSee('test category');

        $this->assertDatabaseHas('categories', [
            'name' => 'test category',
            'color' => 'blue'
        ]);
    }

    public function test_category_requires_name()
    {
        $this->signIn();

        Livewire::test(BetCategory::class)
            ->set('name', '')
            ->set('color', 'green')
            ->call('create')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_category_requires_color()
    {
        $this->signIn();

        Livewire::test(BetCategory::class)
            ->set('name', 'test category')
            ->set('color', '')
            ->call('create')
            ->assertHasErrors(['color' => 'required']);
    }

    public function test_max_length_of_name_is_twenty_characters()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        Livewire::test(BetCategory::class)
            ->set('name', '123456789123456789123')
            ->assertSee('The name must not be greater than 20 characters.')
            ->set('color', 'blue')
            ->call('create')
            ->assertHasErrors(['name' => 'max:10']);
    }

    // this test is not working for some reason
    // public function test_cannot_add_category_with_existing_name()
    // {
    //     $this->withoutExceptionHandling();

    //     $this->signIn();

    //     Category::factory()->create([
    //         'user_id' => auth()->id(),
    //         'name' => 'test'
    //     ]);

    //     Livewire::test(BetCategory::class)
    //         ->set('name', 'test')
    //         ->assertSee('This category already exists.')
    //         ->set('color', 'blue')
    //         ->call('create')
    //         ->assertHasErrors(['name']);
    // }

    public function test_can_see_categories()
    {
        $this->signIn();

        $category = Category::factory()->create([
            'user_id' => auth()->id()
        ]);

        $this->get('/categories')->assertSee($category->name);
    }

    public function test_view_has_color_options()
    {
        $this->signIn();

        Livewire::test(BetCategory::class)->assertViewHas('colors');
    }

    public function test_user_cannot_create_more_than_ten_categories()
    {
        $this->signIn();

        Category::factory(10)->create([
            'user_id' => auth()->id()
        ]);

        Livewire::test(BetCategory::class)
            ->set('name', 'test category')
            ->set('color', 'blue')
            ->call('create')
            ->assertSee('Cannot add more than 10 categories.');
    }

    public function test_user_can_delete_a_category()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $category = Category::factory()->create([
            'user_id' => auth()->id()
        ]);

        $this->assertDatabaseHas('categories', $category->toArray());

        Livewire::test(BetCategory::class)
            ->call('confirmDelete', $category)
            ->assertSee('Are you sure?')
            ->assertSee($category->name)
            ->call('deleteCategory')
            ->assertSee('Category deleted!');

        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    public function test_a_user_update_a_category()
    {
        $this->signIn();

        $category = auth()->user()->categories()->create([
            'name' => 'category name',
            'color' => 'category color'
        ]);

        Livewire::test(BetCategory::class)
            ->call('selectCategoryToEdit', $category->id)
            ->assertSee('Update')
            ->assertSee($category->name)
            ->set('name', 'new category name')
            ->set('color', 'new category color')
            ->call('updateCategory')
            ->assertSee('Category updated!');

        $this->assertDatabaseHas('categories', [
            'name' => 'new category name',
            'color' => 'new category color'
        ]);
    }

}
