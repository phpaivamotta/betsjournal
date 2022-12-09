<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ValueBets extends Component
{
    public $odd_type;
    public $regions;

    public function render()
    {
        return view('livewire.value-bets');
    }
}
