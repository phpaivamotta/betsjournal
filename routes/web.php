<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\BetIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\isEmpty;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// routes for all users
// landing page
Route::view('/', 'welcome')->name('home');

// about page
Route::view('about', 'about')->name('about');

// betting tools
// odds comparison page
Route::view('odds-comparison', 'betting-tools/odds-comparison')->name('odds-comparison');

// world cup page
Route::view('world-cup', 'betting-tools/world-cup')->name('world-cup');

// odd converter page
Route::view('odd-converter', 'betting-tools/odd-converter')->name('odd-converter');

// payout calculator
Route::view('payout-calculator', 'betting-tools/payout-calculator')->name('payout-calculator');

// margin calculator
Route::view('margin-calculator', 'betting-tools/margin-calculator')->name('margin-calculator');

// value bets
Route::get('value-bets', function () {
    return view('betting-tools/value-bets', [
        'sports' => Http::retry(3, 50)->get(
            'https://api.the-odds-api.com/v4/sports/?apiKey=' . config('services.the-odds-api.key')
        )->json()
    ]);
})->name('value-bets');

// value bets params
Route::post('value-bets', function (Request $request) {

    $regions = ['us', 'uk', 'eu', 'au'];
    $regionsQueryStr = '';
    $count = 0;
    foreach ($regions as $region) {
        if ($request->$region) {
            $count += 1;
            $regionsQueryStr .= $request->$region . ',';
        }
    }

    if ($count > 0) {
        $regionsQueryStr = rtrim($regionsQueryStr, ",");
    }

    $matches = Http::retry(3, 50)->get(
        'https://api.the-odds-api.com/v4/sports/' . $request->sport .
            '/odds/?apiKey=' . config('services.the-odds-api.key') .
            '&regions=' . $regionsQueryStr .
            '&oddsFormat=decimal'
    )->json();

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

    // build above array
    // this is the main odds array
    $matchStats = [];
    $allMatches = []; // final array
    foreach ($matches as $match) {
        $bookies = [];
        $matchStats['matchId'] = $match['id'];
        $matchStats['sport'] = $match['sport_key'];
        $matchStats['dateTime'] = $match['commence_time'];
        $matchStats['homeTeam'] = $match['home_team'];
        $matchStats['awayTeam'] = $match['away_team'];

        foreach ($match['bookmakers'] as $bookmaker) {
            $bookmakerName = $bookmaker['key'];
            $outcomes = $bookmaker['markets'][0]['outcomes'];

            $bookies[$bookmakerName] = array_map(function ($outcome) {
                return $outcome['price'];
            }, $outcomes);
        }

        $matchStats['bookies'] = $bookies;

        array_push($allMatches, $matchStats);
    }

    // ddd($allMatches);

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

    // build above array
    // this is the odds aggregate array
    $allOdds = []; // final array
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

        array_push($allOdds, $oddsArr);
    }

    // ddd($allOdds);

    /*
        1 => array:9 [▼
        "matchId" => "93f503d7e15171143459a0d9b85b3e51"
        "sport" => "basketball_ncaab"
        "dateTime" => "2022-12-09T00:00:00Z"
        "home" => "Massachusetts Minutemen"
        "away" => "UMass Lowell River Hawks"
        "homeAvg" => array:2 [▶]
        "awayAvg" => array:2 [▶]
        "drawAvg" => array:2 [▶]
        "valueBets" => array:10 [▶]
      ]
    */

    // loop through every match, get the stats and find the value bets
    $valueBets = [];
    foreach ($allMatches as $match) {
        $homeTeam = $match['homeTeam'];
        $awayTeam = $match['awayTeam'];

        $avgOddsTest['matchId'] = $match['matchId'];
        $avgOddsTest['sport'] = $match['sport'];
        $avgOddsTest['dateTime'] = $match['dateTime'];

        foreach ($allOdds as $odds) {
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

        // ddd($oddStats);

        $avgOddsTest['valueBets'] = array_map(function ($stats) {
            return array_filter($stats, function ($stat) {
                if (isset($stat['isValue']) && $stat['overvalue'] >= 0.1) {
                    return $stat['isValue'];
                }
            });
        }, $oddStats);

        $avgOddsTest['valueBets'] = array_filter($avgOddsTest['valueBets']);

        if (!empty($avgOddsTest['valueBets'])) {
            array_push($valueBets, $avgOddsTest);
        }
    }

    // ddd($valueBets);

    return view('betting-tools/value-bets', [
        'matches' => $valueBets,
        'sports' => Http::retry(3, 50)->get(
            'https://api.the-odds-api.com/v4/sports/?apiKey=' . config('services.the-odds-api.key')
        )->json()
    ]);
});

// routes for registered users
Route::middleware(['auth'])->group(function () {
    // index all bets (LIVEWIRE!!)
    Route::get('bets', BetIndex::class)
        ->name('bets.index');

    // create a new bet
    Route::get('bets/create', [BetController::class, 'create'])
        ->name('bets.create');

    Route::post('bets', [BetController::class, 'store'])
        ->name('bets.store');

    // edit a bet
    Route::get('bets/{bet}/edit', [BetController::class, 'edit'])
        ->name('bets.edit');

    Route::patch('bets/{bet}', [BetController::class, 'update'])
        ->name('bets.update');

    // delete a bet
    Route::delete('bets/{bet}', [BetController::class, 'destroy'])
        ->name('bets.destroy');

    // view all bets stats
    Route::get('stats', [BetController::class, 'stats'])
        ->name('bets.stats');

    // edit profile
    Route::get('edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');
    Route::patch('update-profile', [ProfileController::class, 'update'])->name('update-profile');
});

require __DIR__ . '/auth.php';
