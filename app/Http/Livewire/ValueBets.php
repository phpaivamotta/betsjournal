<?php

namespace App\Http\Livewire;

use App\Services\ValueBetsService;
use Illuminate\Validation\Rule;
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

    public function mount()
    {
        $this->sports = (new ValueBetsService())->getSports();
        $this->sport = $this->sports[0];
    }

    public function render()
    {
        return view('livewire.value-bets');
    }

    protected function rules()
    {
        return [
            'oddFormat' => ['required', Rule::in(['american', 'decimal'])],
            'sport' => ['required', Rule::in($this->sports)],
            'regions' => ['required', 'array', 'max:4'],
            'regions.*' => [Rule::in(['us', 'uk', 'eu', 'au']), 'distinct'],
            'overValue' => ['required', 'numeric', 'between:1,100'],
        ];
    }

    protected $messages = [
        'regions.*.in' => 'A region is invalid.',
        'regions.*.distinct' => 'The regions must be distinct.'
    ];

    // get value bets opportunities for each bet
    public function getValueBets()
    {
        // validate inputs
        $this->validate();

        // transform over value to decimal percent
        $overValueTransformed = $this->overValue / 100;

        $valueBets = (new ValueBetsService())
            ->getValueBets(
                $this->regions,
                $this->sport,
                $overValueTransformed
            );

        $this->matches = $valueBets;
    }
}
