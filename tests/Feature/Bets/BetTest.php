<?php

namespace Tests\Feature;

use App\Models\Bet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BetTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_projects_can_be_created()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        $attributes = Bet::factory()->raw();

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // acting as a user (i.e. auth), post to the bets route and make sure redirected
        $this->actingAs($user)->post('/bets', $attributes)->assertRedirect('/bets');

        // DB confirmation
        $this->assertDatabaseHas('bets', $attributes);

        // Confirm view has non-nullable attributes 
        $this->get('/bets')->assertSee([
            $attributes['match'],
            $attributes['bet_size'],
            $attributes['odds']
        ]);
    }

    public function test_bet_requires_a_match()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the match with an empty string
        $attributes = Bet::factory()->raw(['match' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('match');
    }

    public function test_bet_requires_a_bet_size()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the match with an empty string
        $attributes = Bet::factory()->raw(['bet_size' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('bet_size');
    }

    public function test_bet_requires_odds()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the match with an empty string
        $attributes = Bet::factory()->raw(['odds' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('odds');
    }
}
