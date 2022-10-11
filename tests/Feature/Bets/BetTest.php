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

    public function test_bets_can_be_created()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // $attributes = Bet::factory()->raw();

        // faking the form request that will be postested to the route
        $attributes = Bet::factory()->raw(['odd' => 2.20]);

        $indexDecimalOdd = array_search('decimal_odd', $attributes);
        unset($attributes[$indexDecimalOdd]);

        $indexAmericanOdd = array_search('american_odd', $attributes);
        unset($attributes[$indexAmericanOdd]);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // force user to have a matching bet type than what is being implemented below
        $user->odd_type = 'decimal';

        // acting as a user (i.e. auth), post to the bets route and make sure redirected
        $this->actingAs($user)->post('/bets', $attributes)->assertRedirect('/bets');

        $attributes['decimal_odd'] = 2.20;
        $attributes['american_odd'] = 120;
        unset($attributes['odd']);

        // DB confirmation
        $this->assertDatabaseHas('bets', $attributes);

        // Confirm view has non-nullable attributes
        $odd_type = $user->odd_type;

        $this->get('/bets')->assertSee([
            $attributes['match'],
            $attributes['bet_size'],
            $attributes[$odd_type . '_odd'],
            "You've created a new bet!"
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

    public function test_bet_form_post_requires_odd()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the match with an empty string
        $attributes = Bet::factory()->raw(['odd' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('odd');
    }

    public function test_sport_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->sport);
    }

    public function test_match_date_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->match_date);
    }

    public function test_match_time_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->match_time);
    }

    public function test_bookie_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->bookie);
    }

    public function test_bet_type_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->bet_type);
    }

    public function test_bet_description_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->bet_description);
    }

    public function test_bet_pick_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->bet_pick);
    }

    public function test_match_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->match);
    }

    public function test_bet_size_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee($bet->bet_size);
    }

    public function test_bets_can_be_edited()
    {
        $this->withoutExceptionHandling();

        // create bet
        $bet = Bet::factory()->create();

        // transform bet from object to array so that it can be posted to route
        $bet_array = $bet->toArray();

        // edit specific attribute (match in this case)
        $bet_array['match'] = "Flamengo vs. Barcelona";

        // add odd to request attributes
        $bet_array['odd'] = 2.2;

        // get user
        $user = User::find($bet->user_id);

        // force user to have a matching bet type than what is being implemented below
        $user->odd_type = 'decimal';
        $user->save();

        // patch modified bet array to bets.edit
        $this->actingAs($user)->patch('bets/'.$bet->id, $bet_array)->assertRedirect('/bets');

        // manually set decimal and american odds values, as would be set inside controller
        $bet_array['decimal_odd'] = 2.20;
        $bet_array['american_odd'] = 120;
        unset($bet_array['odd']);

        $this->assertDatabaseHas('bets', $bet_array);

        $this->get('/bets')->assertSee("You've updated a bet!");
    }
}
