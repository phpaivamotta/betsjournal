<?php

namespace App\Http\Livewire;

use App\Http\Requests\UpdateProfileRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ManageProfile extends Component
{
    public $showDeleteProfileModal = false;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $odd_type;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
        $this->odd_type = auth()->user()->odd_type;
    }

    public function render()
    {
        return view('livewire.manage-profile');
    }

    public function rules()
    {
        return (new UpdateProfileRequest)->rules();
    }

    public function updateProfile()
    {
        $attributes = $this->validate();

        // if password is null, remove it from the attributes array so that it will not try to update the password to null in the DB. This would cause an SQL error. 
        if (! $attributes['password']) {
            unset($attributes['password']);
        } else {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $this->resetPassword();

        auth()->user()->update($attributes);

        // shows success message of "Profile updated!"
        $this->dispatchBrowserEvent('profile-updated', ['message' => 'Profile updated!']);
    }

    public function confirmDeleteProfile()
    {
        $this->showDeleteProfileModal = true;
    }

    public function deleteProfile()
    {
        auth()->user()->delete();

        $this->showDeleteProfileModal = false;

        return redirect(RouteServiceProvider::HOME)
            ->with('success', 'Your profile has been deleted.');
    }

    private function resetPassword()
    {
        $this->password = '';
        $this->password_confirmation = '';
    }
}
