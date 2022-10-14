<?php

namespace App\Http\Livewire;

use App\Models\Bet;
use Livewire\Component;
use Livewire\WithPagination;

class BetIndex extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    // public $showEditModal = false;
    public Bet $currentBet;

    public function mount()
    {
        $this->currentBet = new Bet();
    }

    public function render()
    {
        return view('livewire.bet-index', [
            'bets' => Bet::where('user_id', '=', auth()->id() )->latest()->paginate(20),
            'optional_attributes' => Bet::$optional_attributes
        ]);
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

    // public function update()
    // {
    //     ddd('update bet');
    // }
}
