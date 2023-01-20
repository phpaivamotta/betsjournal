<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Services\StatsService;

class StatsController extends Controller
{

    /**
     * @group Stats
     * 
     * Get the stats for all your bets.
     * 
     * See your bet's analysis, done for you so that you don't have to open excel.
     * 
     * @queryParam categories Comma-separated list of categories to filter by. Example: 1,2
     * 
     * @response {
     *     "totalBets": 76,
     *     "totalWinBets": 39,
     *     "totalLossBets": 17,
     *     "totalNaBets": 5,
     *     "totalCOBets": 15,
     *     "averageOdds": 4.398,
     *     "impliedProbability": "22.74",
     *     "actualProbability": "69.64",
     *     "totalGains": 8214.45,
     *     "totalLosses": -43.630000000000564,
     *     "netProfit": 8258.080000000002,
     *     "biggestBet": "299.90",
     *     "biggestPayout": 1363.8203600000002,
     *     "biggestLoss": 286.28
     * }
     */
    public function __invoke()
    {
        $categories = request()->has('categories') ? explode(',', request('categories')) : null;

        // validate categories exist/belong to user
        if (isset($categories)) {
            $userCategories = auth()->user()->categories->pluck('id')->toArray();

            foreach ($categories as $category) {
                if (!in_array($category, $userCategories)) {
                    abort(422, 'The category is invalid.');
                }
            }
        }

        // Query user's bets filtering by category
        $bets = Bet::where('user_id', auth()->user()->id)
            ->withCategories($categories)
            ->get();

        // initialize service class
        $stats = new StatsService($bets);

        return [
            'totalBets' => $bets->count(),
            'totalWinBets' => $bets->where('result', '1')->count(),
            'totalLossBets' => $bets->where('result', '0')->count(),
            'totalNaBets' => $bets->where('result', '')->count(),
            'totalCOBets' => $bets->where('result', '2')->count(),
            'averageOdds' => $stats->averageOdds(auth()->user()->odd_type),
            'impliedProbability' => $stats->impliedProbability(),
            'actualProbability' => $stats->actualProbability(),
            'totalGains' =>  $stats->totalGains(),
            'totalLosses' =>  $stats->totalLosses(),
            'netProfit' =>  $stats->totalGains() - $stats->totalLosses(),
            'biggestBet' => $bets->max('bet_size'),
            'biggestPayout' => $stats->biggestPayout(),
            'biggestLoss' => $stats->biggestLoss(),
        ];
    }
}
