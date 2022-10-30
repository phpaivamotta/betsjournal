<?php

namespace Tests\Feature\Bets;

use App\Http\Livewire\BetIndex;
use App\Models\Bet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use \Carbon\Carbon;

class BetTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_the_component_can_render()
    {
        $component = Livewire::test(BetIndex::class);
 
        $component->assertStatus(200);
    }

    public function test_bets_can_be_created()
    {
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

    public function test_bet_requires_a_bookie()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the bookie with an empty string
        $attributes = Bet::factory()->raw(['bookie' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('bookie');
    }

    public function test_bet_requires_a_bet_size()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the bet_size with an empty string
        $attributes = Bet::factory()->raw(['bet_size' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('bet_size');
    }

    public function test_bet_form_post_requires_odd()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the odd with an empty string
        $attributes = Bet::factory()->raw(['odd' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('odd');
    }

    public function test_bet_requires_a_bet_type()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the bet_type with an empty string
        $attributes = Bet::factory()->raw(['bet_type' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('bet_type');
    }

    public function test_bet_requires_a_bet_pick()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the bet_pick with an empty string
        $attributes = Bet::factory()->raw(['bet_pick' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('bet_pick');
    }

    public function test_bet_requires_a_sport()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the sport with an empty string
        $attributes = Bet::factory()->raw(['sport' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('sport');
    }

    public function test_bet_requires_a_match_date()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the date with an empty string
        $attributes = Bet::factory()->raw(['match_date' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('match_date');
    }

    public function test_bet_requires_a_match_time()
    {
        // create a bet, but have it "raw()" so that it is not persisted to the DB before posting and it is an array instead of an object
        // make sure to override the date with an empty string
        $attributes = Bet::factory()->raw(['match_time' => '']);

        // find the user created by the factory so that it can be used below to post data to an auth route
        $user = User::find($attributes['user_id']);

        // post to route and confirm session has error
        $this->actingAs($user)->post('/bets', $attributes)->assertSessionHasErrors('match_time');
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

        $this->actingAs($user)->get('/bets')->assertSee(Carbon::create($bet->match_date)->toFormattedDateString());
    }

    public function test_match_time_can_be_seen()
    {
        $bet = Bet::factory()->create();

        $user = User::find($bet->user_id);

        $this->actingAs($user)->get('/bets')->assertSee(Carbon::create($bet->match_time)->format('h:i A'));
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

        $this->actingAs($user)->get('/bets')->assertSee( ucwords($bet->bet_pick) );
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
        $this->actingAs($user)->patch('bets/' . $bet->id, $bet_array)->assertRedirect('/bets');

        // manually set decimal and american odds values, as would be set inside controller
        $bet_array['decimal_odd'] = 2.20;
        $bet_array['american_odd'] = 120;
        unset($bet_array['odd']);

        $this->assertDatabaseHas('bets', $bet_array);

        $this->get('/bets')->assertSee("Bet updated!");
    }

    public function test_user_can_delete_bet()
    {
        $this->actingAs(User::factory()->create());

        $bet = Bet::factory()->create();

        Livewire::test(BetIndex::class)
            ->call('confirmDelete', $bet->id)
            ->assertSee('Are you sure?')
            ->call('deleteBet')
            ->assertSee('Bet deleted!');
    }

    public function test_win_checkbox_filter_works()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $bet_win = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 1
        ]);

        $bet_loss = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 0
        ]);

        $bet_na = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => null
        ]);

        Livewire::test(BetIndex::class)
            ->assertSee([$bet_win->match, $bet_loss->match, $bet_na->match])
            ->set('win', true)
            ->assertDontSee([$bet_loss->match, $bet_na->match])
            ->assertSee($bet_win->match);
    }

    public function test_loss_checkbox_filter_works()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $bet_win = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 1
        ]);

        $bet_loss = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 0
        ]);

        $bet_na = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => null
        ]);

        Livewire::test(BetIndex::class)
            ->assertSee([$bet_win->match, $bet_loss->match, $bet_na->match])
            ->set('loss', true)
            ->assertDontSee([$bet_win->match, $bet_na->match])
            ->assertSee($bet_loss->match);
    }

    public function test_na_checkbox_filter_works()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $bet_win = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 1
        ]);

        $bet_loss = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 0
        ]);

        $bet_na = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => null
        ]);

        Livewire::test(BetIndex::class)
            ->assertSee([$bet_win->match, $bet_loss->match, $bet_na->match])
            ->set('na', true)
            ->assertDontSee([$bet_win->match, $bet_loss->match])
            ->assertSee($bet_na->match);
    }

    public function test_search_filter_works()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $bet = Bet::factory()->create([
            'user_id' => $user->id
        ]);

        Livewire::test(BetIndex::class)
            ->assertSee($bet->match)
            ->set('search', 'gibberish')
            ->assertDontSee($bet->match)
            ->set('search', $bet->match)
            ->assertSee($bet->match);
    }

    public function test_filters_can_be_combined()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $bet_win = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 1
        ]);

        $bet_loss = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => 0
        ]);

        $bet_na = Bet::factory()->create([
            'user_id' => $user->id,
            'result' => null
        ]);

        Livewire::test(BetIndex::class)
            ->assertSee([$bet_win->match, $bet_loss->match, $bet_na->match])
            ->set('win', true)
            ->assertDontSee([$bet_loss->match, $bet_na->match])
            ->assertSee($bet_win->match)
            ->set('loss', true)
            ->assertDontSee([$bet_na->match])
            ->assertSee([$bet_win->match, $bet_loss->match])
            ->set('search', $bet_na->match)
            ->assertDontSee($bet_na->match)
            ->set('na', true)
            ->assertSee($bet_na->match)
            ->set('search', '')
            ->assertSee([$bet_win->match, $bet_loss->match, $bet_na->match]);
    }
}
