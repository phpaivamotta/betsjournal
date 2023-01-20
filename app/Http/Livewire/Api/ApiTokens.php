<?php

namespace App\Http\Livewire\Api;

use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Component;

class ApiTokens extends Component
{
    public $token_name;
    public $currentToken;
    public $showDeleteModal = false;
    public $showTokenModal = false;

    public function mount()
    {
        $this->currentToken = new PersonalAccessToken();
    }

    public function render()
    {
        return view('livewire.api.api-tokens');
    }

    protected $rules = [
        'token_name' => ['required']
    ];

    public function createToken()
    {
        $this->validate();

        $token = auth()->user()->createToken($this->token_name);

        $this->token_name = '';

        $this->showTokenModal = true;

        session()->flash('token', $token->plainTextToken);
    }

    public function confirmDelete(PersonalAccessToken $token)
    {
        $this->currentToken = $token;

        $this->showDeleteModal = true;
    }

    public function deleteToken()
    {
        auth()->user()->tokens()->where('id', $this->currentToken->id)->delete();

        // not sure why I need to do this to avoid 404 error...
        $this->currentToken = new PersonalAccessToken();

        $this->showDeleteModal = false;

        session()->flash('success', 'Token deleted!');
    }
}
