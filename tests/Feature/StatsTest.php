<?php

namespace Tests\Feature;

use App\Models\Bet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_view_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/stats');

        $response->assertStatus(200);
    }

    public function test_total_bets_count()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/stats')->assertViewHas('totalBets', 0);

        Bet::factory(5)->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/stats')->assertViewHas('totalBets', 5);
    }

    public function test_total_win_bets()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/stats')->assertViewHas('totalBets', 0);

        Bet::factory(2)->create([
            'user_id' => $user->id,
            'result' => 1
        ]);

        $this->actingAs($user)->get('/stats')->assertViewHas('totalWinBets', 2);
    }

    public function test_total_loss_bets()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/stats')->assertViewHas('totalLossBets', 0);

        Bet::factory(2)->create([
            'user_id' => $user->id,
            'result' => 0
        ]);

        $this->actingAs($user)->get('/stats')->assertViewHas('totalLossBets', 2);
    }

    public function test_total_na_bets()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/stats')->assertViewHas('totalNaBets', 0);

        Bet::factory(2)->create([
            'user_id' => $user->id,
            'result' => null
        ]);

        $this->actingAs($user)->get('/stats')->assertViewHas('totalNaBets', 2);
    }

    public function test_average_decimal_odds()
    {
        $user = User::factory()->create(['odd_type' => 'decimal']);

        $bets = Bet::factory(4)->create(['user_id' => $user->id]);

        $avgOdds = number_format($bets->avg('decimal_odd'), 3);

        $this->actingAs($user)->get('/stats')->assertViewHas('averageOdds', $avgOdds);
    }

    public function test_average_american_odds()
    {
        $user = User::factory()->create(['odd_type' => 'american']);

        $bets = Bet::factory(4)->create(['user_id' => $user->id]);

        $avgOdds = number_format($bets->avg('american_odd'), 3);

        $this->actingAs($user)->get('/stats')->assertViewHas('averageOdds', $avgOdds);
    }

    public function test_implied_probability()
    {
        $user = User::factory()->create();

        $bet = Bet::factory()->create(['user_id' => $user->id]);

        $implied_prob = number_format((1 / $bet->decimal_odd) * 100, 2);

        $this->actingAs($user)->get('/stats')->assertViewHas('impliedProbability', $implied_prob);
    }

    public function test_total_gains()
    {
        $user = User::factory()->create();

        $bets = Bet::factory(5)->create(['user_id' => $user->id]);

        $totalPayoffs = $bets->map(function ($bet) {
            if ($bet->result) {
                return $bet->payoff();
            }
        })->sum();

        $this->actingAs($user)->get('/stats')->assertViewHas('totalGains', $totalPayoffs);
    }

    public function test_total_losses()
    {
        $user = User::factory()->create();

        $bets = Bet::factory(5)->create(['user_id' => $user->id]);

        $totLosses = $bets->where('result', 0)->sum('bet_size');

        $this->actingAs($user)->get('/stats')->assertViewHas('totalLosses', $totLosses);
    }

    public function test_biggest_bet()
    {
        $user = User::factory()->create();

        $bets = Bet::factory(5)->create(['user_id' => $user->id]);

        $biggestBet = $bets->max('bet_size');

        $this->actingAs($user)->get('/stats')->assertViewHas('biggestBet', $biggestBet);
    }

    public function test_biggest_payoff()
    {
        $user = User::factory()->create();

        $bets = Bet::factory(5)->create(['user_id' => $user->id]);

        $biggestPayoff = $bets->map(function ($bet) {
            if ($bet->result === true) {
                return $bet->payoff();
            }
        })->max();

        $this->actingAs($user)->get('/stats')->assertViewHas('biggestPayoff', $biggestPayoff);
    }

    public function test_biggest_loss()
    {
        $user = User::factory()->create();

        $bets = Bet::factory(5)->create(['user_id' => $user->id]);

        $biggestLoss = $bets->where('result', 0)->max('bet_size');

        $this->actingAs($user)->get('/stats')->assertViewHas('biggestLoss', $biggestLoss);
    }

    public function test_bet_results()
    {
        $user = User::factory()->create();

        $bets = Bet::factory(15)->create(['user_id' => $user->id]);

        $betResults = $bets->groupBy('result')
            ->map(fn ($betResults) => $betResults->count())
            ->toArray();

        $betResultsSort = [
            isset($betResults[1]) ? $betResults[1] : 0,
            isset($betResults[0]) ? $betResults[0] : 0,
            isset($betResults[null]) ? $betResults[null] : 0
        ];

        $this->actingAs($user)->get('/stats')->assertViewHas('betResultsSort', $betResultsSort);
    }

    public function test_net_profit()
    {
        $user = User::factory()->create();

        $bets = Bet::factory(15)->create(['user_id' => $user->id]);

        $profitArray = [];

        // get loss or payoff for every bet
        foreach ($bets as $bet) {
            if ($bet->result === 1) {
                $profitArray[] = $bet->payoff();
            } else if ($bet->result === 0) {
                $profitArray[] = - ((float) $bet->bet_size);
            }
        }

        $netProfitArr = [];

        // find the cumulative sum of bets 
        // e.g., if bet1 = $100, bet2 = $50, and bet3 = -$30
        // then the $netProfitArr = [1 => 100, 2 => (100 + 50), 3 => (100 + 50 -30)]
        // which gives an array with the total profit at a given bet number
        for ($i = 0; $i < count($profitArray); $i++) {
            if ($i > 0) {
                $netProfitArr[($i + 1)] = $netProfitArr[$i] + $profitArray[$i];
            } else {
                $netProfitArr[($i + 1)] = $profitArray[$i];
            }
        }

        $netProfitArr = array_map(function ($profit) {
            return round($profit, 2);
        }, $netProfitArr);

        $this->actingAs($user)->get('/stats')->assertSee('netProfit', $netProfitArr);
    }
}
