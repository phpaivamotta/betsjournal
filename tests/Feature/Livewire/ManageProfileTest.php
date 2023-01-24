<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ManageProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ManageProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_its_profile_info()
    {
        $this->signIn();
    
        $this->get('/profile')->assertStatus(200)
            ->assertSee(auth()->user()->name)
            ->assertSee(auth()->user()->email);
    }

    public function test_guest_cannot_access_route()
    {
        $this->get('/profile')->assertRedirect('login');
    }

    public function test_user_can_delete_own_profile()
    {
        $this->signIn();

        $user = auth()->user();

        $this->assertDatabaseHas('users', $user->toArray());
    
        Livewire::test(ManageProfile::class)
            ->call('confirmDeleteProfile')
            ->assertSee('Are you sure?')
            ->assertSee('Once your account is deleted, you can no longer recover it.')
            ->call('deleteProfile');

        $this->get('/')->assertSee('Your profile has been deleted.');

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    public function test_user_can_edit_profile()
    {
        $this->signIn();
    
        Livewire::test(ManageProfile::class)
            ->set('name', 'new name')
            ->set('email', 'newemail@email.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('odd_type', 'american')
            ->call('updateProfile')
            ->assertDispatchedBrowserEvent('profile-updated', ['message' => 'Profile updated!']);

        $this->assertDatabaseHas('users', [
            'name' => 'new name',
            'email' => 'newemail@email.com',
            'odd_type' => 'american'
        ]);

        $this->assertTrue(Hash::check('password', auth()->user()->password));
    }

    public function test_update_profile_requires_name()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        Livewire::test(ManageProfile::class)
            ->set('name', '')
            ->set('email', 'newemail@email.com')
            ->call('updateProfile')
            ->assertSee('The name field is required.');
    }

    public function test_update_profile_requires_email()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        Livewire::test(ManageProfile::class)
            ->set('name', 'new name')
            ->set('email', '')
            ->call('updateProfile')
            ->assertSee('The email field is required.');
    }
}
