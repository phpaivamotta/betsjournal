<?php

namespace App\Http\Livewire;

use App\Models\Bet;
use Livewire\Component;
use Livewire\WithPagination;

class BetIndex extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public Bet $currentBet;
    public $search = '';
    public $page = 1;
    public $win;
    public $loss;
    public $na;
    public $cashout;
    public $categories = [];

    public function mount()
    {
        $this->currentBet = new Bet();
    }

    public function render()
    {
        return view('livewire.bet-index', [
            'bets' => Bet::where('user_id', '=', auth()->id() )
                ->withCategories($this->categories)
                ->filter($this->search, $this->win, $this->loss, $this->na, $this->cashout)
                ->orderBy('match_date', 'desc')
                ->orderBy('match_time', 'desc')
                ->paginate(20),
        ]);
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
}
