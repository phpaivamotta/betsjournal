<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Services\StatsService;

class StatsController extends Controller
{
    public function __invoke()
    {
        $categories = request('categories');

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

        return view('bets.stats', [
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
            'biggestBet' => $bets->max('bet_size'),
            'biggestPayout' => $stats->biggestPayout(),
            'biggestLoss' => $stats->biggestLoss(),

            // charts
            'betResultsSort' => $stats->resultsCount(),
            'netProfit' => $stats->netProfit(),
            'resultCountProbRange' => $stats->resultCountProbabilityRange(),
            'monthlyProfit' => $stats->monthlyProfit()
        ]);
    }
}
