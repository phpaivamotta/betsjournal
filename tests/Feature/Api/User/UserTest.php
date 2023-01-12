<?php

namespace Tests\Feature\Api\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_user_can_get_own_info_from_api()
    {
        $this->signIn();

        $this->getJson('/api/v1/user')
            ->assertStatus(200)
            ->assertJson([
                "id" => auth()->id(),
                "name" => auth()->user()->name,
                "email" => auth()->user()->email,
                "odd_type" => auth()->user()->odd_type,
            ]);
    }

    public function test_guest_cannot_access_user_api_route()
    {
        $this->getJson('/api/v1/user')->assertStatus(401);    
    }
}
