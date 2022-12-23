<?php

namespace Tests\Feature;

use App\Models\Bet;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BetCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_categories_screen_loads()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get('/categories')->assertStatus(200);
    }

    public function test_a_user_can_create_a_bet()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = [
            'name' => 'test category',
            'color' => '#da82be'
        ];

        $this->post("/categories", $category);

        $this->get("/categories")->assertSee($category['name']);
        $this->assertDatabaseHas('categories', $category);
    }

    public function test_a_category_requires_a_name()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = [
            'name' => '',
            'color' => '#da82be'
        ];

        $this->post("/categories", $category)->assertSessionHasErrors('name');
    }

    public function test_a_category_requires_a_color()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = [
            'name' => 'test',
            'color' => ''
        ];

        $this->post("/categories", $category)->assertSessionHasErrors('color');
    }

    public function test_a_category_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user);

        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->patch("/categories/{$category->id}", [
            'name' => 'new category',
            'color' => 'new color'
        ]);

        $this->assertDatabaseHas('categories',  [
            'name' => 'new category',
            'color' => 'new color'
        ]);
    }
}
