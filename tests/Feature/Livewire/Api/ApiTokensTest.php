<?php

namespace Tests\Feature\Livewire\Api;

use App\Http\Livewire\Api\ApiTokens;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApiTokensTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        $this->signIn();

        $component = Livewire::test(ApiTokens::class);

        $component->assertStatus(200);
    }

    public function test_guest_cannot_access_view()
    {
        $this->get('/api-tokens')->assertStatus(302)->assertRedirect('login');    
    }

    public function test_user_can_create_api_token()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        Livewire::test(ApiTokens::class)
            ->set('token_name', 'test-token')
            ->call('createToken')
            ->assertSee('Personal Access Token')
            ->assertSee('Please copy your API token:');
    }

    public function test_api_token_requires_name()
    {
        $this->signIn();
    
        Livewire::test(ApiTokens::class)
            ->set('token_name', '')
            ->call('createToken')
            ->assertHasErrors(['token_name' => 'required']);
    }

    public function test_user_can_see_own_api_token_names()
    {
        $this->signIn();

        auth()->user()->createToken('test-token');

        $this->get('/api-tokens')->assertSee('test-token');
    }

    public function test_user_can_delete_tokens()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();

        $token = auth()->user()->createToken('test-token');

        Livewire::test(ApiTokens::class)
            ->call('confirmDelete', $token->accessToken->id)
            ->assertSee('Do you really wish to delete this token?')
            ->call('deleteToken')
            ->assertSee('Token deleted!');
    }
}
