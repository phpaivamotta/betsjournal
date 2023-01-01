<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBetRequest;
use App\Http\Requests\UpdateBetRequest;
use App\Models\Bet;
use App\Services\ConvertOddsService;

class BetController extends Controller
{
    public function create()
    {
        return view('bets.create');
    }

    public function store(StoreBetRequest $request)
    {
        $attributes = $request->validated();

        // get user prefence for odd type (american or decimal),
        // validate accordingly then store it into attributes array as respective odd_type value (american_odd) or (decimal_odd)
        // convert american to decimal or vice-versa depending on user odd_type preference
        $user_odd_type = auth()->user()->odd_type;

        if ($user_odd_type === 'american') {

            $attributes['american_odd'] = $attributes['odd'];

            $attributes['decimal_odd'] = ConvertOddsService::americanToDecimal($attributes['american_odd']);

        } elseif ($user_odd_type === 'decimal') {

            
            $attributes['decimal_odd'] = $attributes['odd'];

            $attributes['american_odd'] = ConvertOddsService::decimalToAmerican($attributes['decimal_odd']);

        }

        // remove odd field since it has already been transformed into american and decimal odds
        unset($attributes['odd']);

        // get user id from auth instead of request
        $attributes['user_id'] = auth()->id();

        // persist
        $bet = Bet::create($attributes);

        // attach categories if any
        $bet->categories()->attach($request['categories']);

        // redirect
        return redirect('/bets')->with('success', "You've created a new bet!");
    }

    public function edit(Bet $bet)
    {
        return view('bets.edit', ['bet' => $bet]);
    }

    public function update(UpdateBetRequest $request, Bet $bet)
    {
        $attributes = $request->validated();

        // get user prefence for odd type (american or decimal),
        // validate accordingly then store it into attributes array as respective odd_type value (american_odd) or (decimal_odd)
        // convert american to decimal or vice-versa depending on user odd_type preference
        $user_odd_type = auth()->user()->odd_type;

        if ($user_odd_type === 'american') {

            $attributes['american_odd'] = $attributes['odd'];

            $attributes['decimal_odd'] = ConvertOddsService::americanToDecimal($attributes['american_odd']);

        } elseif ($user_odd_type === 'decimal') {

            $attributes['decimal_odd'] = $attributes['odd'];

            $attributes['american_odd'] = ConvertOddsService::decimalToAmerican($attributes['decimal_odd']);

        }

        // remove odd field since it has already been transformed into american and decimal odds
        unset($attributes['odd']);

        $bet->update($attributes);

        // sync categories
        $bet->categories()->sync($request['categories']);

        return redirect()
            ->route('bets.index', ['page' => request('page')])
            ->with('success', "Bet updated!");
    }

    public function stats()
    {
        $bets = Bet::where('user_id', auth()->user()->id)
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->get();

        $avgDecimalOdds = $bets->avg('decimal_odd');
        $avgAmericanOdds = $bets->avg('american_odd');

        // calculate net profit as the number of bets increases
        $profitArray = [];

        foreach ($bets as $bet) {
            if ($bet->result === 1) {
                $profitArray[] = $bet->payout() - $bet->bet_size;
            } else if ($bet->result === 0) {
                $profitArray[] = - ((float) $bet->bet_size);
            }
        }

        $netProfitArr = [];

        for ($i = 0; $i < count($profitArray); $i++) {
            if ($i > 0) {
                $netProfitArr[($i + 1)] = $netProfitArr[$i] + $profitArray[$i];
            } else {
                $netProfitArr[($i + 1)] = $profitArray[$i];
            }
        }

        $netProfitArr = array_map(function ($profit) {
            return round($profit, 2);
        }, $netProfitArr);


        // calculate result count distribution in the prob. range

        // group bet results
        $resultGroups = Bet::where('user_id', auth()->user()->id)
            ->get()
            ->groupBy('result')
            ->map(
                fn ($resultBin) => $resultBin
                    ->map(
                        fn ($bet) => floor(((float) rtrim($bet->impliedProbability(), "%")) / 10)
                    )->countBy()
            )->toArray();

        $resultOptions = [
            'wins' => isset($resultGroups[1]) ? $resultGroups[1] : null,
            'losses' => isset($resultGroups[0]) ? $resultGroups[0] : null,
            'na' => isset($resultGroups[null]) ? $resultGroups[null] : null
        ];

        // this is the order in which the bet results are returned in $resultGroups
        $resultOrder = [
            'wins',
            'losses',
            'na'
        ];

        $dataArr = [];
        $count = 0;
        foreach ($resultOptions as $resultGroup) {

            $oneResultArr = [];

            for ($i = 0; $i <= 10; $i++) {
                // 10 is a special case because impliedProbability() rounds results close to 99% up
                // by default
                if ($i === 10) {
                    if (isset($resultGroup[$i])) {
                        $oneResultArr[$i - 1] += $resultGroup[$i];
                    } else {
                        $oneResultArr[$i - 1] += 0;
                    }
                } else {
                    if (isset($resultGroup[$i])) {
                        $oneResultArr[$i] = $resultGroup[$i];
                    } else {
                        $oneResultArr[$i] = 0;
                    }
                }
            }

            $dataArr[$resultOrder[$count]] = $oneResultArr;
            $count++;
        }

        // calculate win/loss/na
        $betResults = Bet::where('user_id', auth()->user()->id)
            ->get()
            ->groupBy('result')
            ->map(fn ($betResults) => $betResults->count())
            ->toArray();

        $betResultsSort = [
            isset($betResults[1]) ? $betResults[1] : 0,
            isset($betResults[0]) ? $betResults[0] : 0,
            isset($betResults[null]) ? $betResults[null] : 0
        ];

        return view('bets.stats', [
            'totalBets' => Bet::where('user_id', auth()->user()->id)
                ->count(),

            'totalWinBets' => Bet::where('user_id', auth()->user()->id)
                ->where('result', 1)->count(),

            'totalLossBets' => Bet::where('user_id', auth()->user()->id)
                ->where('result', 0)->count(),

            'totalNaBets' => Bet::where('user_id', auth()->user()->id)
                ->where('result', null)->count(),

            'averageOdds' => auth()->user()->odd_type === 'decimal' ? number_format($avgDecimalOdds, 3) : number_format($avgAmericanOdds, 3),

            'impliedProbability' => $avgDecimalOdds ? number_format(100 * (1 / $avgDecimalOdds), 2)
                : null,

            'totalGains' =>  Bet::where('user_id', auth()->user()->id)
                ->where('result', 1)
                ->get()
                ->map(fn ($bet) => $bet->payout() - $bet->bet_size)
                ->sum(),

            'totalLosses' =>  Bet::where('user_id', auth()->user()->id)
                ->where('result', 0)
                ->pluck('bet_size')
                ->sum(),

            'biggestBet' => Bet::where('user_id', auth()->user()->id)
                ->max('bet_size'),

            'biggestPayout' => Bet::where('user_id', auth()->user()->id)
                ->where('result', 1)
                ->get()
                ->map(fn ($bet) => $bet->payout())
                ->max(),

            'biggestLoss' => Bet::where('user_id', auth()->user()->id)
                ->where('result', 0)
                ->max('bet_size'),

            // charts
            'betResultsSort' => $betResultsSort,

            'netProfit' => $netProfitArr,

            'resultCountProbRange' => $dataArr

        ]);
    }
}
