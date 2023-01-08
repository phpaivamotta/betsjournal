<?php

namespace Tests\Unit;

use App\Services\ValueBetsService;
use PHPUnit\Framework\TestCase;

class ValueBetsTest extends TestCase
{
    public function test_matches_array_is_accurate()
    {
        $responseArray = self::fakeSportOddsApiResponseArray();
        $matchesArray = (new ValueBetsService())->buildMatchesArray($responseArray);

        $this->assertEquals('AIK', $matchesArray[0]['homeTeam']);
        $this->assertEquals('BIK Karlskoga', $matchesArray[0]['awayTeam']);
        $this->assertEquals(2, count($matchesArray));
        $this->assertEquals(4, count($matchesArray[0]['bookies']));
        $this->assertEquals(3, count($matchesArray[0]['bookies']['unibet_eu']));
        $this->assertEquals(3, count($matchesArray[0]['bookies']['betclic']));
        $this->assertEquals(2, count($matchesArray[0]['bookies']['sport888']));
        $this->assertEquals(2, count($matchesArray[0]['bookies']['pinnacle']));
    }

    public function test_averag_odds_array_is_accurate()
    {
        $response = self::fakeSportOddsApiResponseArray();
        $service = new ValueBetsService();
        $matches = $service->buildMatchesArray($response);
        $avgOdds = $service->averageOddsArray($matches);

        $this->assertEquals(2.02, number_format($avgOdds[0]['AIK']['averageOdds'], 2));
        $this->assertEquals(4, $avgOdds[0]['AIK']['numBookies']);

        $this->assertEquals(2.28, number_format($avgOdds[0]['BIK Karlskoga']['averageOdds'], 2));
        $this->assertEquals(4, $avgOdds[0]['BIK Karlskoga']['numBookies']);

        $this->assertEquals(4.15, number_format($avgOdds[0]['draw']['averageOdds'], 2));
        $this->assertEquals(2, $avgOdds[0]['draw']['numBookies']);
    }

    private static function fakeSportOddsApiResponseArray()
    {
        return [

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
        ];
    }
}
