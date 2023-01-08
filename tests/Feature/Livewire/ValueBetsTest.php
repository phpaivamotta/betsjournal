<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ValueBets;
use App\Services\ValueBetsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ValueBetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_component_can_render()
    {
        $component = Livewire::test(ValueBets::class);

        $component->assertStatus(200);
    }

    public function test_can_receive_in_season_sports_from_api()
    {
        // API response
        $sports = (new ValueBetsService())->getSports();

        Livewire::test(ValueBets::class)
            ->assertViewHas('sports', $sports)
            ->assertSee($sports);
    }

    public function test_odd_format_regions_and_over_value_are_required()
    {
        Livewire::test(ValueBets::class)
            ->set('oddFormat', '')
            ->set('sport', '')
            ->set('regions', '')
            ->set('overValue', '')
            ->call('getValueBets')
            ->assertHasErrors([
                'oddFormat' => 'required',
                'sport' => 'required',
                'regions' => 'required',
                'overValue' => 'required',
            ])
            ->assertSee('The odd format field is required.')
            ->assertSee('The sport field is required.')
            ->assertSee('The regions field is required.')
            ->assertSee('The over value field is required.');
    }

    public function test_odd_format_must_be_american_or_decimal()
    {
        Livewire::test(ValueBets::class)
            ->set('oddFormat', 'not_american_or_decimal')
            ->call('getValueBets')
            ->assertHasErrors(['oddFormat' => 'in'])
            ->assertSee('The selected odd format is invalid.');
    }

    public function test_sport_must_be_in_api_sports_list()
    {
        Livewire::test(ValueBets::class)
            ->set('sport', 'not_any_sport')
            ->call('getValueBets')
            ->assertHasErrors(['sport' => 'in'])
            ->assertSee('The selected sport is invalid.');
    }

    public function test_regions_must_be_in_api_regions_list()
    {
        Livewire::test(ValueBets::class)
            ->set('regions', ['jupiter', 'mars'])
            ->call('getValueBets')
            ->assertHasErrors([
                'regions.0' => 'in',
                'regions.1' => 'in'
            ])
            ->assertSee('A region is invalid.');
    }

    public function test_regions_array_cannot_have_more_than_four_elements()
    {
        Livewire::test(ValueBets::class)
            ->set('regions', ['one', 'two', 'three', 'four', 'five'])
            ->call('getValueBets')
            ->assertHasErrors(['regions' => 'max'])
            ->assertSee('The regions must not have more than 4 items.');
    }

    public function test_regions_must_be_distinct()
    {
        Livewire::test(ValueBets::class)
            ->set('regions', ['us', 'us', 'eu', 'au'])
            ->call('getValueBets')
            ->assertHasErrors(['regions.1' => 'distinct']);
    }

    public function test_over_value_must_be_in_range()
    {
        Livewire::test(ValueBets::class)
            ->set('overValue', -200)
            ->call('getValueBets')
            ->assertHasErrors(['overValue' => 'between'])
            ->assertSee('The over value must be between 1 and 100.')
            ->set('overValue', 200)
            ->call('getValueBets')
            ->assertHasErrors(['overValue' => 'between'])
            ->assertSee('The over value must be between 1 and 100.');
    }

    public function test_can_see_value_bets()
    {
        $url = 'https://api.the-odds-api.com/v4/sports/icehockey_sweden_allsvenskan/odds/?apiKey='
            . config('services.the-odds-api.key') .
            '&regions=eu&oddsFormat=decimal';

        Http::fake([
            $url => $this->fakeSportOddsApiResponse()
        ]);

        Livewire::test(ValueBets::class)
            ->set('oddFormat', 'decimal')
            ->set('sport', 'icehockey_sweden_allsvenskan')
            ->set('regions', ['eu'])
            ->set('overValue', 1)
            ->call('getValueBets')
            ->assertSee('AIK vs BIK Karlskoga')
            ->assertSee('Money Line')
            ->assertSee('Overvalue')
            ->assertSee('#Bookies Analyzed')
            ->assertSee('Record');
    }

    public function test_can_see_no_value_bets_found_message()
    {
        $url = 'https://api.the-odds-api.com/v4/sports/icehockey_sweden_allsvenskan/odds/?apiKey='
            . config('services.the-odds-api.key') .
            '&regions=eu&oddsFormat=decimal';

        Http::fake([
            $url => $this->fakeSportOddsApiResponse()
        ]);

        Livewire::test(ValueBets::class)
            ->set('oddFormat', 'decimal')
            ->set('sport', 'icehockey_sweden_allsvenskan')
            ->set('regions', ['eu'])
            ->set('overValue', 100) // set over value to 100% to guarantee no value bets
            ->call('getValueBets')
            ->assertSee('No value bets found for this sport/region.');
    }

    public function test_can_record_value_bets()
    {
        $this->signIn();
    
        $this->get('value-bets/record', [
            'match' => 'Real Madrid vs Barcelona',
            'bookie' => 'bet365',
            'odd' => 2.35,
            'betPick' => 'Real Madrid',
            'sport' => 'Soccer',
            'date' => \Carbon\Carbon::create(1975, 12, 25, 14, 15, 16)->toDateString(),
            'time' => \Carbon\Carbon::create(1975, 12, 25, 14, 15, 16)->format('H:i'),
        ])->assertStatus(200);
    }

    public function test_value_bets_accuracy()
    {
        $url = 'https://api.the-odds-api.com/v4/sports/icehockey_sweden_allsvenskan/odds/?apiKey='
            . config('services.the-odds-api.key') .
            '&regions=eu&oddsFormat=decimal';

        Http::fake([
            $url => $this->fakeSportOddsApiResponse()
        ]);

        $valueBets = (new ValueBetsService())
            ->getValueBets(
                ['eu'],
                'icehockey_sweden_allsvenskan',
                0.01
            );

        // number of matches 
        $this->assertEquals(2, count($valueBets));

        // home team odds average
        $this->assertEquals(2.02, number_format($valueBets[0]['homeAvg']['averageOdds'], 2));
        $this->assertEquals(4, $valueBets[0]['homeAvg']['numBookies']);
        
        // away team odds average
        $this->assertEquals(2.28, number_format($valueBets[0]['awayAvg']['averageOdds'], 2));
        $this->assertEquals(4, $valueBets[0]['awayAvg']['numBookies']);

        // draw odds average
        $this->assertEquals(4.15, number_format($valueBets[0]['drawAvg']['averageOdds'], 2));
        $this->assertEquals(2, $valueBets[0]['drawAvg']['numBookies']);

        // value bets

        // AIK vs BIK Karlskoga
        $this->assertEquals(2, count($valueBets[0]['valueBets']));
        // unibet_eu
        $this->assertEquals(3, count($valueBets[0]['valueBets']['unibet_eu']));
        $this->assertEquals(0.0706, number_format($valueBets[0]['valueBets']['unibet_eu']['homeOdds']['overvalue'], 4));
        $this->assertEquals(0.1829, number_format($valueBets[0]['valueBets']['unibet_eu']['awayOdds']['overvalue'], 4));
        $this->assertEquals(0.0361, number_format($valueBets[0]['valueBets']['unibet_eu']['drawOdds']['overvalue'], 4));
        // betclic
        $this->assertEquals(2, count($valueBets[0]['valueBets']['betclic']));
        $this->assertEquals(0.1400, number_format($valueBets[0]['valueBets']['betclic']['homeOdds']['overvalue'], 4));
        $this->assertEquals(0.0953, number_format($valueBets[0]['valueBets']['betclic']['awayOdds']['overvalue'], 4));

        // Kristianstads IK vs Södertälje SK
        $this->assertEquals(2, count($valueBets[1]['valueBets']));
        // unibet_eu
        $this->assertEquals(3, count($valueBets[1]['valueBets']['unibet_eu']));
        $this->assertEquals(0.1111, number_format($valueBets[1]['valueBets']['unibet_eu']['homeOdds']['overvalue'], 4));
        $this->assertEquals(0.1450, number_format($valueBets[1]['valueBets']['unibet_eu']['awayOdds']['overvalue'], 4));
        $this->assertEquals(0.0361, number_format($valueBets[1]['valueBets']['unibet_eu']['drawOdds']['overvalue'], 4));
        // betclic
        $this->assertEquals(2, count($valueBets[1]['valueBets']['betclic']));
        $this->assertEquals(0.1325, number_format($valueBets[1]['valueBets']['betclic']['homeOdds']['overvalue'], 4));
        $this->assertEquals(0.0941, number_format($valueBets[1]['valueBets']['betclic']['awayOdds']['overvalue'], 4));
    }

    private function fakeSportOddsApiResponse()
    {
        return Http::response([

            [
                "id" => "977dc36612b093d3651459bedbe54809",
                "sport_key" => "icehockey_sweden_allsvenskan",
                "sport_title" => "HockeyAllsvenskan",
                "commence_time" => "2023-01-06T14:00:00Z",
                "home_team" => "AIK",
                "away_team" => "BIK Karlskoga",
                "bookmakers" => [
                    [
                        "key" => "unibet_eu",
                        "title" => "Unibet",
                        "last_update" => "2023-01-05T02:31:57Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:31:57Z",
                                "outcomes" => [
                                    [
                                        "name" => "AIK",
                                        "price" => 2.16,
                                    ],
                                    [
                                        "name" => "BIK Karlskoga",
                                        "price" => 2.7,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 4.3,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "key" => "betclic",
                        "title" => "Betclic",
                        "last_update" => "2023-01-05T02:31:32Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:31:32Z",
                                "outcomes" => [
                                    [
                                        "name" => "AIK",
                                        "price" => 2.3,
                                    ],
                                    [
                                        "name" => "BIK Karlskoga",
                                        "price" => 2.5,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 4.0,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "key" => "sport888",
                        "title" => "888sport",
                        "last_update" => "2023-01-05T02:28:34Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:28:34Z",
                                "outcomes" => [
                                    [
                                        "name" => "AIK",
                                        "price" => 1.8,
                                    ],
                                    [
                                        "name" => "BIK Karlskoga",
                                        "price" => 2.0,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "key" => "pinnacle",
                        "title" => "Pinnacle",
                        "last_update" => "2023-01-05T02:32:42Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:32:42Z",
                                "outcomes" => [
                                    [
                                        "name" => "AIK",
                                        "price" => 1.81,
                                    ],
                                    [
                                        "name" => "BIK Karlskoga",
                                        "price" => 1.93,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                "id" => "52c85f1c210b5d52a6ddb874f8b2e526",
                "sport_key" => "icehockey_sweden_allsvenskan",
                "sport_title" => "HockeyAllsvenskan",
                "commence_time" => "2023-01-06T14:00:00Z",
                "home_team" => "Kristianstads IK",
                "away_team" => "Södertälje SK",
                "bookmakers" => [
                    [
                        "key" => "unibet_eu",
                        "title" => "Unibet",
                        "last_update" => "2023-01-05T02:31:57Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:31:57Z",
                                "outcomes" => [
                                    [
                                        "name" => "Kristianstads IK",
                                        "price" => 2.6,
                                    ],
                                    [
                                        "name" => "Södertälje SK",
                                        "price" => 2.25,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 4.3,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "key" => "betclic",
                        "title" => "Betclic",
                        "last_update" => "2023-01-05T02:31:32Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:31:32Z",
                                "outcomes" => [
                                    [
                                        "name" => "Kristianstads IK",
                                        "price" => 2.65,
                                    ],
                                    [
                                        "name" => "Södertälje SK",
                                        "price" => 2.15,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 4.0,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "key" => "sport888",
                        "title" => "888sport",
                        "last_update" => "2023-01-05T02:28:34Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:28:34Z",
                                "outcomes" => [
                                    [
                                        "name" => "Kristianstads IK",
                                        "price" => 2.05,
                                    ],
                                    [
                                        "name" => "Södertälje SK",
                                        "price" => 1.75,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "key" => "pinnacle",
                        "title" => "Pinnacle",
                        "last_update" => "2023-01-05T02:32:42Z",
                        "markets" => [
                            [
                                "key" => "h2h",
                                "last_update" => "2023-01-05T02:32:42Z",
                                "outcomes" => [
                                    [
                                        "name" => "Kristianstads IK",
                                        "price" => 2.06,
                                    ],
                                    [
                                        "name" => "Södertälje SK",
                                        "price" => 1.71,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ], 200);
    }
}
