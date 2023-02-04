<?php

namespace Tests\Feature\Email;

use App\Mail\ValueBetsMail;
use App\Models\Subscriber;
use App\Services\EmailValueBetsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailablesSentTest extends TestCase
{
    use RefreshDatabase;

    public function test_value_bets_mail_sent()
    {
        Mail::fake();

        $url = 'https://api.the-odds-api.com/v4/sports/*/odds/*';

        Http::fake([
            $url => $this->fakeSportOddsApiResponse()
        ]);

        Subscriber::factory()->create();

        (new EmailValueBetsService)->sendValueBetsEmail();

        Mail::assertSent(ValueBetsMail::class);
    }

    public function test_value_bets_mail_sent_to_new_subscriber()
    {
        Mail::fake();

        $url = 'https://api.the-odds-api.com/v4/sports/*/odds/*';

        Http::fake([
            $url => $this->fakeSportOddsApiResponse()
        ]);

        $this->post('/subscribers', [
            'subscriber-email' => 'test@email.com'
        ]);

        Mail::assertSent(ValueBetsMail::class);
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
