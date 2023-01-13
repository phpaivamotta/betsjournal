<?php

namespace Tests\Feature\Api\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_available_category_colors()
    {
        $this->signIn();

        $this->getJson('/api/v1/categories/colors')
            ->assertStatus(200)
            ->assertJson([
                "blue",
                "indigo",
                "brown",
                "black",
                "yellow"
            ]);
    }

    public function test_user_can_see_his_categories()
    {
        $this->signIn();

        Category::factory()->create([
            'user_id' => auth()->id(),
            'name' => 'test category',
            'color' => 'brown'
        ]);

        $this->getJson('/api/v1/categories')
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'data',
                    1,
                    fn ($json) =>
                    $json->where('name', 'test category')
                        ->where('color', 'brown')
                        ->etc()
                )
            );
    }

    public function test_guest_cannot_access_route()
    {
        Category::factory(5)->create();

        $this->getJson('/api/v1/categories')->assertStatus(401);
    }

    public function test_user_can_see_a_single_category()
    {
        $this->signIn();

        $category = Category::factory()->create([
            'user_id' => auth()->id()
        ]);

        $this->getJson("/api/v1/categories/{$category->id}")
            ->assertStatus(200);
    }

    public function test_guest_cannot_access_category_route()
    {
        $category = Category::factory()->create();

        $this->getJson("/api/v1/categories/{$category->id}")
            ->assertStatus(401);
    }

    public function test_user_cannot_see_another_users_category()
    {
        $this->signIn();
    
        $category = Category::factory()->create();

        $this->getJson("/api/v1/categories/{$category->id}")
            ->assertStatus(403);
    }

    public function test_user_can_create_category()
    {
        $this->signIn();
    
        $response = $this->postJson('/api/v1/categories', [
            'name' => 'test category',
            'color' => 'brown'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'test category',
                'color' => 'brown'
            ]);
    }

    public function test_user_can_update_category()
    {
        $this->signIn();
    
        $category = Category::factory()->create([
            'user_id' => auth()->id(),
            'name' => 'category',
            'color' => 'indigo',
        ]);

        $response = $this->patchJson("/api/v1/categories/{$category->id}", [
            'name' => 'updated name',
            'color' => 'blue'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'updated name')
            ->assertJsonPath('data.color', 'blue');
    }

    public function test_user_cannot_update_another_users_category()
    {
        $category = Category::factory()->create();

        $user = User::factory()->create();

        $this->actingAs($user)->patchJson("/api/v1/categories/{$category->id}", [
            'name' => 'updated name',
            'color' => 'blue'
        ])->assertStatus(403);
    }

    public function test_user_can_delete_category()
    {
        $this->signIn();
    
        $category = Category::factory()->create([
            'user_id' => auth()->id()
        ]);

        $this->deleteJson("/api/v1/categories/{$category->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    public function test_another_user_cannot_delete_category()
    {
        $this->signIn();

        $category = Category::factory()->create();

        $this->deleteJson("/api/v1/categories/{$category->id}")
            ->assertStatus(403);
    }
}
