<?php

namespace Tests\Feature\Api\Bet;

use App\Models\Bet;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_own_bets_from_api()
    {
        $this->signIn();

        $bets = Bet::factory(2)->create([
            'user_id' => auth()->id()
        ]);

        $this->getJson('/api/v1/bets')
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data', 2)
                    ->has('links')
                    ->has('meta')
                    ->has(
                        'data.0',
                        fn ($json) =>
                        $json->where('id', $bets[0]->id)
                            ->missing('user_id')
                            ->where('sport', $bets[0]->sport)
                            ->where('match', $bets[0]->match)
                            ->etc()
                    )
            );
    }

    public function test_guest_cannot_access_bets_route()
    {
        $this->getJson('/api/v1/bets')
            ->assertStatus(401)
            ->assertJson([
                'message' => "Unauthenticated."
            ]);
    }

    public function test_user_can_get_just_one_bet_from_api()
    {
        $this->signIn();

        $bets = Bet::factory(5)->create([
            'user_id' => auth()->id()
        ]);

        $this->getJson("/api/v1/bets/{$bets[0]->id}")
            ->assertStatus(200);
    }

    public function test_user_cannot_see_another_users_bet()
    {
        $this->signIn();
    
        $bets = Bet::factory(5)->create();

        $this->getJson("/api/v1/bets/{$bets[0]->id}")
            ->assertStatus(403);
    }

    public function test_bet_has_category()
    {
        $this->signIn();

        $bet = Bet::factory()->create([
            'user_id' => auth()->id()
        ]);

        $category = Category::factory()->create([
            'user_id' => auth()->id()
        ]);

        $bet->categories()->attach($category->id);

        $this->getJson("/api/v1/bets/{$bet->id}")
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
                    ->has('data.categories', 1)
                    ->etc()
            );
    }

    public function test_user_can_create_bet()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $bet = Bet::factory()->raw([
            'user_id' => auth()->id(),
            'odd' => 2.20
        ]);

        $this->postJson('/api/v1/bets', $bet)
            ->assertStatus(201);
    }

    public function test_user_can_update_bet()
    {
        $user = User::factory()->create([
            'odd_type' => 'decimal'
        ]);

        $this->actingAs($user);
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id(),
            'decimal_odd' => 1.12
        ]);

        $updatedBet = Bet::factory()->raw([
            'user_id' => auth()->id(),
            'odd' => 4.5
        ]);

        $this->putJson("/api/v1/bets/{$bet->id}", $updatedBet)
            ->assertStatus(200)
            ->assertJsonPath('data.decimal_odd', '4.500');
    }

    public function test_user_cannot_update_another_users_bet()
    {
        $this->signIn();
    
        $otherUserBet = Bet::factory()->create();

        $updatedBet = Bet::factory()->raw([
            'user_id' => auth()->id(),
            'odd' => 2.2
        ]);
        
        $this->putJson("/api/v1/bets/{$otherUserBet->id}", $updatedBet)
            ->assertStatus(403);
    }

    public function test_user_can_delete_bet()
    {
        $this->withoutExceptionHandling();
    
        $this->signIn();
    
        $bet = Bet::factory()->create([
            'user_id' => auth()->id()
        ]);

        $this->deleteJson("/api/v1/bets/{$bet->id}")
            ->assertStatus(200);
    }

    public function test_user_cannot_delete_another_users_bet()
    {
        $this->signIn();
    
        $bet = Bet::factory()->create();

        $this->deleteJson("/api/v1/bets/{$bet->id}")
            ->assertStatus(403);
    }
}
