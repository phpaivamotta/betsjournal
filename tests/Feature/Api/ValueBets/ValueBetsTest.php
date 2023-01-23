<?php

namespace Tests\Feature\Api\ValueBets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ValueBetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_available_sports()
    {
        $this->signIn();

        $this->getJson('/api/v1/value-bets/sports')
            ->assertStatus(200);
    }

    public function test_user_can_get_value_bets()
    {
        $this->signIn();

        $url = 'https://api.the-odds-api.com/v4/sports/icehockey_sweden_allsvenskan/odds/?apiKey='
            . config('services.the-odds-api.key') .
            '&regions=eu&oddsFormat=decimal';

        Http::fake([
            $url => $this->fakeSportOddsApiResponse()
        ]);

        $this->getJson('api/v1/value-bets?sport=icehockey_sweden_allsvenskan&regions=eu&overValue=5')
            ->assertStatus(200);
    }

    public function test_guest_cannot_get_value_bets()
    {
        $this->getJson('api/v1/value-bets?sport=icehockey_sweden_allsvenskan&regions=eu&overValue=5')
            ->assertStatus(401);
    }

    /**
     * returns example list of available in-season sports from the-odds-api
     */
    private function fakeSportsList()
    {
        return Http::response([
            [
                "key" => "americanfootball_nfl",
                "group" => "American Football",
                "title" => "NFL",
                "description" => "US Football",
                "active" => true,
                "has_outrights" => false
            ],
            [
                "key" => "americanfootball_nfl_super_bowl_winner",
                "group" => "American Football",
                "title" => "NFL Super Bowl Winner",
                "description" => "Super Bowl Winner 2022/2023",
                "active" => true,
                "has_outrights" => true
            ],
            [
                "key" => "aussierules_afl",
                "group" => "Aussie Rules",
                "title" => "AFL",
                "description" => "Aussie Football",
                "active" => true,
                "has_outrights" => false
            ],
            [
                "key" => "baseball_mlb_world_series_winner",
                "group" => "Baseball",
                "title" => "MLB World Series Winner",
                "description" => "World Series Winner 2023",
                "active" => true,
                "has_outrights" => true
            ],
            [
                "key" => "basketball_euroleague",
                "group" => "Basketball",
                "title" => "Basketball Euroleague",
                "description" => "Basketball Euroleague",
                "active" => true,
                "has_outrights" => false
            ]
        ]);
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
                                        "price" => 2.0,
                                    ],
                                    [
                                        "name" => "BIK Karlskoga",
                                        "price" => 2.0,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 6.0,
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
                                        "price" => 5.0,
                                    ],
                                    [
                                        "name" => "BIK Karlskoga",
                                        "price" => 2.0,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 2.0,
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
                                        "price" => 2.0,
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
                                        "price" => 2.0,
                                    ],
                                    [
                                        "name" => "BIK Karlskoga",
                                        "price" => 4.0,
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
                                        "name" => "Södertälje SK",
                                        "price" => 6.0,
                                    ],
                                    [
                                        "name" => "Kristianstads IK",
                                        "price" => 3.0,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 3.0,
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
                                        "price" => 3.0,
                                    ],
                                    [
                                        "name" => "Södertälje SK",
                                        "price" => 3.0,
                                    ],
                                    [
                                        "name" => "Draw",
                                        "price" => 3.0,
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
                                        "price" => 3.0,
                                    ],
                                    [
                                        "name" => "Södertälje SK",
                                        "price" => 3.0,
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
                                        "price" => 3.0,
                                    ],
                                    [
                                        "name" => "Södertälje SK",
                                        "price" => 3.0,
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
