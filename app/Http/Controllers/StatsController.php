<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Services\StatsService;

class StatsController extends Controller
{
    public function __invoke()
    {
        // Query user's bets filtering by category
        $bets = Bet::where('user_id', auth()->user()->id)
            ->withCategories(request('categories'))
            ->get();

        // initialize service class
        $stats = new StatsService($bets);

        return view('bets.stats', [
            'totalBets' => $bets->count(),
            'totalWinBets' => $bets->where('result', '1')->count(),
            'totalLossBets' => $bets->where('result', '0')->count(),
            'totalNaBets' => $bets->where('result', '')->count(),
            'averageOdds' => $stats->averageOdds(auth()->user()->odd_type),
            'impliedProbability' => $stats->impliedProbability(),
            'totalGains' =>  $bets->where('result', '1')
                ->map(fn ($bet) => $bet->payout() - $bet->bet_size)
                ->sum(),
            'totalLosses' =>  $bets->where('result', '0')
                ->pluck('bet_size')
                ->sum(),
            'biggestBet' => $bets->max('bet_size'),
            'biggestPayout' => $bets->where('result', '1')
                ->map(fn ($bet) => $bet->payout())
                ->max(),
            'biggestLoss' => $bets->where('result', '0')
                ->max('bet_size'),

            // charts
            'betResultsSort' => $stats->resultsCount(),
            'netProfit' => $stats->netProfit(),
            'resultCountProbRange' => $stats->resultCountProbabilityRange()
        ]);
    }
}
