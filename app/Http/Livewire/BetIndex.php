<?php

namespace App\Http\Livewire;

use App\Models\Bet;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class BetIndex extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $showResolveModal = false;
    public Bet $currentBet;
    public $search = '';
    public $page = 1;
    public $win;
    public $loss;
    public $na;
    public $cashout; // for filtering
    public $result;
    public $cashoutAmount;
    public $categories = [];

    public function mount()
    {
        $this->currentBet = new Bet();
    }

    public function render()
    {
        return view('livewire.bet-index', [
            'bets' => Bet::where('user_id', '=', auth()->id())
                ->withCategories($this->categories)
                ->filter($this->search, $this->win, $this->loss, $this->na, $this->cashout)
                ->orderBy('match_date', 'desc')
                ->orderBy('match_time', 'desc')
                ->paginate(20),
        ]);
    }

    protected function rules()
    {
        return  [
            'result' => ['required', Rule::in([0, 1, 2])],
            'cashoutAmount' => ['exclude_unless:result,2', 'required', 'numeric', 'min:0']
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $queryString = [
        'win' => ['except' => false],
        'loss' => ['except' => false],
        'na' => ['except' => false],
        'categories',
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingWin()
    {
        $this->resetPage();
    }

    public function updatingLoss()
    {
        $this->resetPage();
    }

    public function updatingNa()
    {
        $this->resetPage();
    }

    public function updatingCashout()
    {
        $this->resetPage();
    }

    public function updatingCategories()
    {
        $this->resetPage();
    }

    public function confirmDelete(Bet $bet)
    {
        $this->currentBet = $bet;

        $this->showDeleteModal = true;
    }

    public function deleteBet()
    {
        $this->currentBet->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Bet deleted!');
    }

    public function confirmResolve(Bet $bet)
    {
        $this->currentBet = $bet;

        $this->showResolveModal = true;
    }

    public function resolveBet()
    {
        $attributes = $this->validate();

        // because I already have a cashout variable named in this class for filtering,
        // I needed to name the cashout amount in currency, which appears in the resolveBet modal
        // as cashoutAmount
        // because of this, I need to rename this key in the array in order to update the bet
        if ($attributes['result'] == 2) {
            $renamedAttributes['cashout'] = $attributes['cashoutAmount']; 
        }

        $renamedAttributes['result'] = $attributes['result']; 

        $this->currentBet->update($renamedAttributes);

        $this->resetAttributes();

        session()->flash('success', 'Bet resolved!');
    }

    public function resetAttributes()
    {
        $this->result = null;
        $this->cashoutAmount = null;
        $this->showResolveModal = false;
    }
}
