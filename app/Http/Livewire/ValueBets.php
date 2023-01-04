<?php

namespace App\Http\Livewire;

use App\Services\ValueBetsService;
use Livewire\Component;

class ValueBets extends Component
{
    // odd format (american or decimal)
    public $oddFormat;

    // list of sport markets offered by the API
    public $sports;

    // the selected sport
    public $sport;

    // value bets overvalue percentage in decimal (e.g., 0.1 = 10%)
    public $overValue;

    // market regions supported by the API 
    public $regions = [];

    // value bets array
    public $matches;

    // validation rules
    protected $rules = [
        'oddFormat' => 'required',
        'regions' => 'required',
        'overValue' => ['required', 'min:1', 'max:100'],
    ];

    public function mount()
    {
        $this->sports = (new ValueBetsService())->getSports();
    }

    public function render()
    {
        return view('livewire.value-bets');
    }

    // get value bets opportunities for each bet
    public function getValueBets()
    {
        // validate inputs
        $this->validate();

        // transform over value to decimal percent
        $this->overValue = $this->overValue / 100;

        // check if user has not selected a sport
        if (!$this->sport) {
            // if not, then set the first sport as the one selected
            $this->sport = $this->sports[0]['key'];
        }

        $valueBets = (new ValueBetsService())
            ->getValueBets(
                $this->sports,
                $this->regions,
                $this->sport,
                $this->overValue
            );

        $this->matches = $valueBets;
    }
}
