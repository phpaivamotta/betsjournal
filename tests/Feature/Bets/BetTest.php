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
        // so that error can be more easily visualized
        $this->withoutExceptionHandling();

        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is in array format instead of object
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
}
