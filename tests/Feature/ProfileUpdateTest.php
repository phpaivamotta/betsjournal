<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_edit_profile_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/edit-profile');

        $response->assertStatus(200);
    }

    public function test_user_can_edit_profile()
    {
        $user = User::factory()->create();

        $attributes = [
            'name' => 'myname',
            'email' => 'myemail@email.com',
            'odd_type' => 'american'
        ];

        $this->actingAs($user)->patch('update-profile', $attributes);

        $this->assertDatabaseHas('users', $attributes);
    }
}
