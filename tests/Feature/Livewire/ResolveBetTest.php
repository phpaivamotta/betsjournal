<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\BetIndex;
use App\Models\Bet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ResolveBetTest extends TestCase
{
    public function test_bet_can_be_resolved()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();

        $bet = Bet::factory()->create([
            'user_id' => auth()->id()
        ]);
    
        Livewire::test(BetIndex::class)
            ->call('confirmResolve', $bet->id)
            ->assertSee('Resolve')
            ->assertSee($bet->match);
    }
}
