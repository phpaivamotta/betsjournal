<?php

namespace Tests\Feature\Api\Stats;

use App\Models\Bet;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_stats()
    {
        $user = User::factory()->create([
            'odd_type' => 'decimal'
        ]);

        $this->actingAs($user);

        Bet::factory(4)->create([
            'user_id' => auth()->id(),
            'decimal_odd' => 2.00,
            'american_odd' => 500,
            'result' => 1
        ]);
    
        $this->getJson('/api/v1/bets/stats')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('totalBets', 4)
                    ->where('totalWinBets', 4)
                    ->where('totalLossBets', 0)
                    ->where('totalNaBets', 0)
                    ->where('totalCOBets', 0)
                    ->where('averageOdds', 2)
                    ->where('impliedProbability', "50.00")
                    ->where('actualProbability', "100.00")
                    ->has('totalGains')
                    ->has('totalLosses')
                    ->has('netProfit')
                    ->has('biggestBet')
                    ->has('biggestPayout')
                    ->has('biggestLoss')
            );
    }

    public function test_guest_cannot_access_route()
    {
        $this->getJson('/api/v1/bets/stats')
            ->assertStatus(401);
    }

    public function test_stats_can_be_filtered()
    {
        $this->signIn();

        $bets = Bet::factory(4)->create([
            'user_id' => auth()->id(),
            'decimal_odd' => 2.00,
            'american_odd' => 500,
            'result' => 1
        ]);

        $categories = Category::factory(2)->create([
            'user_id' => auth()->id()
        ]);

        $bets[0]->categories()->attach($categories[0]->id);
        $bets[1]->categories()->attach($categories[0]->id);
        $bets[2]->categories()->attach($categories[1]->id);
        $bets[3]->categories()->attach($categories[1]->id);
    
        $this->getJson('/api/v1/bets/stats')
            ->assertJson([
                'totalBets' => 4
            ]);

        // filter bets by first category 
        $response = $this->getJson('/api/v1/bets/stats?categories=1');
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('totalBets', 2)
                ->etc()
        );

        // filter bets by second category 
        $response = $this->getJson('/api/v1/bets/stats?categories=2');
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('totalBets', 2)
                ->etc()
        );

        // filter bets by both bets
        $response = $this->getJson('/api/v1/bets/stats?categories=1,2');
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('totalBets', 4)
                ->etc()
        );
    }

    public function test_cannnot_filter_by_wrong_category()
    {
        $this->signIn();

        $bets = Bet::factory(4)->create([
            'user_id' => auth()->id(),
            'decimal_odd' => 2.00,
            'american_odd' => 500,
            'result' => 1
        ]);

        $categories = Category::factory(2)->create([
            'user_id' => auth()->id()
        ]);

        $bets[0]->categories()->attach($categories[0]->id);
        $bets[1]->categories()->attach($categories[0]->id);
        $bets[2]->categories()->attach($categories[1]->id);
        $bets[3]->categories()->attach($categories[1]->id);

        $this->getJson('/api/v1/bets/stats?categories=3')
            ->assertStatus(422);
    }
}
