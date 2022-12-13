<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ValueBets extends Component
{
    // odd format (american or decimal)
    public $oddFormat;

    // list of sport markets offered by the API
    public $sports;

    // the selected sport
    public $sport;

    // value bets overvalue percentage
    public $overValue = 0.01;

    // market regions supported by the API 
    public $regions = [];

    // value bets array
    public $matches;

    // validation rules
    protected $rules = [
        'oddFormat' => 'required',
        'regions' => 'required',
    ];

    public function mount()
    {
        $this->sports = Http::retry(3, 50)->get(
            'https://api.the-odds-api.com/v4/sports/?apiKey=' . config('services.the-odds-api.key')
        )->json();
    }

    public function render()
    {
        return view('livewire.value-bets');
    }

    // get the odds from the-odds-api
    public function requestOdds()
    {
        // validate inputs
        $this->validate();

        // join selected regions in coma separated string
        $regionsQStr = join(',', $this->regions);

        // check if user has not selected a sport
        if (!$this->sport) {
            // if not, then set the first sport as the one selected
            $this->sport = $this->sports[0]['key'];
        }

        // call odds API endpoint and return response
        // note: for some reason, api call only works when I retry
        return Http::retry(3, 50)->get(
            'https://api.the-odds-api.com/v4/sports/' . $this->sport .
                '/odds/?apiKey=' . config('services.the-odds-api.key') .
                '&regions=' . $regionsQStr .
                '&oddsFormat=decimal'
        );
    }

    // returns an array with all matches and its bookies' money-line odds
    public function buildMatchesArray($matches)
    {
        // this function builds the array below
        /*
        [
            0 => [
                'matchId' => '$match['id']',
                'sport' => '$match['sport_key']',
                'dateTime' => '$match['commence_time']',
                'homeTeam' => '$match['home_team']',
                'awayTeam' => '$match['away_team']',
                'bookies' => [
                    'bookmakerA' => [
                        'homeTeam' => 'homeTeamOdd',
                        'awayTeam' => 'awayTeamOdd',
                        'draw' => 'drawOdd',
                    ],
                    'bookmakerB' => [
                        'homeTeam' => 'homeTeamOdd',
                        'awayTeam' => 'awayTeamOdd',
                        'draw' => 'drawOdd',
                    ],
                    ...
                ]
            ],
            1 => [
                ...
            ], 
        ]
        */

        $allMatches = []; // all matches
        $matchStats = []; // individual match
        foreach ($matches as $match) {
            $bookies = [];
            $matchStats['matchId'] = $match['id'];
            $matchStats['sport'] = $match['sport_key'];
            $matchStats['dateTime'] = $match['commence_time'];
            $matchStats['homeTeam'] = $match['home_team'];
            $matchStats['awayTeam'] = $match['away_team'];

            // loop through each bookmaker    
            foreach ($match['bookmakers'] as $bookmaker) {
                // get the bookmaker name
                $bookmakerName = $bookmaker['key'];
                // get the outcomes array for this bookmaker
                $outcomes = $bookmaker['markets'][0]['outcomes'];
                // store the price of each outcome in an associative array
                $bookies[$bookmakerName] = array_map(function ($outcome) {
                    return $outcome['price'];
                }, $outcomes);
            }

            // put the bookies array into matchStats
            $matchStats['bookies'] = $bookies;

            // push it to array with all matchStats
            array_push($allMatches, $matchStats);

            // return array with all matches
            return $allMatches;
        }
    }

    // get the average odds for each match money-line outcome
    public function averageOddsArray($allMatches)
    {
        // This function builds the array below
        /*
            0 => [
                "Darren Till" => [
                "averageOdds" => 2.5392857142857,
                "numBookies" => 14
                ],
                "Dricus Du Plessis" => [
                "averageOdds" => 1.5364285714286,
                "numBookies" => 14
                ],
                "draw" => [
                "averageOdds" => null,
                "numBookies" => 0
                ]
            ]
        */

        $averageOdds = [];
        foreach ($allMatches as $match) {
            $homeTeamOdds = [];
            $awayTeamOdds = [];
            $drawOdds = [];

            foreach ($match['bookies'] as $bookie) {
                array_push($homeTeamOdds, $bookie[0]);
                array_push($awayTeamOdds, $bookie[1]);

                if (isset($bookie[2])) {
                    array_push($drawOdds, $bookie[2]);
                }
            }

            $oddsArr = [
                $match['homeTeam'] => [
                    'averageOdds' => array_sum($homeTeamOdds) / count($homeTeamOdds),
                    'numBookies' => count($homeTeamOdds)
                ],
                $match['awayTeam'] => [
                    'averageOdds' => array_sum($awayTeamOdds) / count($awayTeamOdds),
                    'numBookies' => count($awayTeamOdds)
                ],
                'draw' => [
                    'averageOdds' => empty($drawOdds) ? null : array_sum($drawOdds) / count($drawOdds),
                    'numBookies' => count($drawOdds)
                ]
            ];

            array_push($averageOdds, $oddsArr);

            // return array with all average odds for each match line
            return $averageOdds;
        }
    }

    // get value bets opportunities for each bet
    public function getValueBets()
    {
        // This function returns an array like the one below
        /*
        [▼
        "matchId" => "cf7ac70607ba6548f76cb5082682fcf3"
        "sport" => "soccer_china_superleague"
        "dateTime" => "2022-12-14T07:00:00Z"
        "home" => "Dalian Professional"
        "away" => "Wuhan Three Towns"
        "homeAvg" => array:2 [▼
            "averageOdds" => 7.18
            "numBookies" => 5
        ]
        "awayAvg" => array:2 [▼
            "averageOdds" => 1.366
            "numBookies" => 5
        ]
        "drawAvg" => array:2 [▼
            "averageOdds" => 4.92
            "numBookies" => 5
        ]
        "valueBets" => array:4 [▼
            "sportsbet" => array:2 [▼
                "homeOdds" => array:5 [▼
                    "moneyline" => 7.5
                    "oddsDiff" => 0.32
                    "oddsRatio" => 1.0445682451253
                    "overvalue" => 0.044568245125348
                    "isValue" => true
                    ]
                "drawOdds" => array:5 [▼
                    "moneyline" => 5.0
                    "oddsDiff" => 0.08
                    "oddsRatio" => 1.0162601626016
                    "overvalue" => 0.016260162601626
                    "isValue" => true
                    ]
                ]
            "pointsbetau" => array:1 [▶]
            "betfair" => array:3 [▶]
            "unibet" => array:2 [▶]
            ]
        ]
        */

        // get the odds for the chosen API parameters
        $response = $this->requestOdds();

        // if the response failed, flash error message and return
        if ($response->failed()) {
            session()->flash('error', 'Sorry, we could not connect to the API right now.');
            return $this->valueBets = null;
        }

        $allMatches = $this->buildMatchesArray($response->json());
        $averageOdds = $this->averageOddsArray($allMatches);

        // loop through every match, get the stats and find the value bets
        $valueBets = [];
        foreach ($allMatches as $match) {
            $homeTeam = $match['homeTeam'];
            $awayTeam = $match['awayTeam'];

            $avgOddsTest['matchId'] = $match['matchId'];
            $avgOddsTest['sport'] = $match['sport'];
            $avgOddsTest['dateTime'] = $match['dateTime'];

            // loop through the average odds of a given match
            foreach ($averageOdds as $odds) {
                // check if the teams from the odds array are the same ones from the match array
                // if they are, then record their average odds values 
                if (isset($odds[$homeTeam]) && isset($odds[$awayTeam])) {
                    $homeTeamAverageOdds = $odds[$homeTeam]['averageOdds'];
                    $homeTeamNumBookies = $odds[$homeTeam]['numBookies'];

                    $awayTeamAverageOdds = $odds[$awayTeam]['averageOdds']; // issue
                    $awayTeamNumBookies = $odds[$awayTeam]['numBookies'];

                    $drawAverageOdds = $odds['draw']['averageOdds'];
                    $drawNumBookies = $odds['draw']['numBookies'];
                }
            }

            $avgOddsTest['home'] = $homeTeam;
            $avgOddsTest['away'] = $awayTeam;

            $avgOddsTest['homeAvg'] = [
                'averageOdds' => $homeTeamAverageOdds,
                'numBookies' => $homeTeamNumBookies
            ];

            $avgOddsTest['awayAvg'] = [
                'averageOdds' => $awayTeamAverageOdds,
                'numBookies' => $awayTeamNumBookies
            ];

            $avgOddsTest['drawAvg'] = [
                'averageOdds' => $drawAverageOdds,
                'numBookies' => $drawNumBookies
            ];

            // loop through each bookie and record their stats
            // INCLUDING IF IT IS A VALUE BET
            $oddStats = [];
            foreach ($match['bookies'] as $key => $value) {
                $oddStats[$key] = [
                    'homeOdds' => [
                        'moneyline' => $value[0],
                        'oddsDiff' => $value[0] - $homeTeamAverageOdds,
                        'oddsRatio' => $value[0] / $homeTeamAverageOdds,
                        'overvalue' => ($value[0] / $homeTeamAverageOdds) - 1,
                        'isValue' => $value[0] - $homeTeamAverageOdds > 0 ? true : false
                    ],
                    'awayOdds' => [
                        'moneyline' => $value[1],
                        'oddsDiff' => $value[1] - $awayTeamAverageOdds,
                        'oddsRatio' => $value[1] / $awayTeamAverageOdds,
                        'overvalue' => ($value[1] / $awayTeamAverageOdds) - 1,
                        'isValue' => $value[1] - $awayTeamAverageOdds > 0 ? true : false
                    ],
                    'drawOdds' => isset($value[2]) ?
                        [
                            'moneyline' => $value[2],
                            'oddsDiff' => $value[2] - $drawAverageOdds,
                            'oddsRatio' => $value[2] / $drawAverageOdds,
                            'overvalue' => ($value[2] / $drawAverageOdds) - 1,
                            'isValue' => $value[2] - $drawAverageOdds > 0 ? true : false
                        ]
                        :
                        null
                ];
            }

            // find the value bets
            $avgOddsTest['valueBets'] = array_map(function ($stats) {
                return array_filter($stats, function ($stat) {
                    // filter the array for value bets that are above a certain overvalue percentage
                    if (isset($stat['isValue']) && $stat['overvalue'] >= $this->overValue) {
                        return $stat['isValue'];
                    }
                });
            }, $oddStats);

            // remove the bookies that have no valuebets
            $avgOddsTest['valueBets'] = array_filter($avgOddsTest['valueBets']);

            // if there any value bets found for the given match, then add it to the $valueBets array
            if (!empty($avgOddsTest['valueBets'])) {
                array_push($valueBets, $avgOddsTest);
            }
        }

        // set the value bets array
        return $this->matches = $valueBets;
    }
}
