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
    use RefreshDatabase;
    
    public function test_resolve_modal_can_be_seen()
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

    public function test_bet_win_can_be_resolved()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id(),
            'result' => 0
        ]);
    
        Livewire::test(BetIndex::class)
            ->call('confirmResolve', $bet->id)
            ->set('result', 1)
            ->call('resolveBet')
            ->assertSee('Bet resolved!');

        $this->assertDatabaseHas('bets', ['result' => 1]);
    }

    public function test_bet_loss_can_be_resolved()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id(),
            'result' => 1
        ]);
    
        Livewire::test(BetIndex::class)
            ->call('confirmResolve', $bet->id)
            ->set('result', 0)
            ->call('resolveBet')
            ->assertSee('Bet resolved!');

        $this->assertDatabaseHas('bets', ['result' => 0]);
    }

    public function test_bet_cashout_can_be_resolved()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id(),
            'result' => 0
        ]);
    
        Livewire::test(BetIndex::class)
            ->call('confirmResolve', $bet->id)
            ->set('result', 2)
            ->set('cashoutAmount', 100)
            ->call('resolveBet')
            ->assertSee('Bet resolved!');

        $this->assertDatabaseHas('bets', ['result' => 2, 'cashout' => 100]);
    }

    public function test_bet_requires_result()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id()
        ]);
    
        Livewire::test(BetIndex::class)
            ->call('confirmResolve', $bet->id)
            ->call('resolveBet')
            ->assertSee('The result field is required.');
    }

    public function test_cashout_requires_amount()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id()
        ]);
    
        Livewire::test(BetIndex::class)
            ->call('confirmResolve', $bet->id)
            ->set('result', 2)
            ->call('resolveBet')
            ->assertSee('The cashout amount field is required.');
    }

    public function test_can_see_resolve_button_on_na_bet()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id(),
            'result' => null
        ]);

        $this->get('/bets')->assertSee('Resolve');
    }
}
