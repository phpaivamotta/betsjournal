<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BetController extends Controller
{
    public function create()
    {
        return view('bets.create');
    }

    public function store(Request $request)
    {
        // validate
        $attributes = $request->validate([
            'match' => ['required', 'string', 'max:255'],
            'bet_size' => ['required', 'numeric', 'min:0'],

            'sport' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['required', 'date_format:H:i'],
            'bookie' => ['required', 'string', 'max:255'],
            'bet_type' => ['required', 'string', 'max:255'],
            'bet_description' => ['nullable', 'string', 'max:255'],
            'bet_pick' => ['required', 'string', 'max:255'],
            'result' => ['nullable', Rule::in([0, 1, 2])],
            'cashout' => ['exclude_unless:result,2', 'required', 'numeric', 'min:0']
        ]);

        // get user prefence for odd type (american or decimal),
        // validate accordingly then store it into attributes array as respective odd_type value (american_odd) or (decimal_odd)
        // convert american to decimal or vice-versa depending on user odd_type preference
        $user_odd_type = auth()->user()->odd_type;

        if ($user_odd_type === 'american') {

            // validate method returns an array ['odd' => $odd]
            $american_odd_array = $request->validate([
                'odd' => ['required', 'numeric', 'min:-10000', 'max:100000']
            ]);

            // so it is unpacked here
            $attributes['american_odd'] = $american_odd_array['odd'];

            $attributes['decimal_odd'] = self::americanToDecimal($attributes['american_odd']);
        } elseif ($user_odd_type === 'decimal') {

            // validate method returns an array ['odd' => $odd]
            $decimal_odd_array = $request->validate([
                'odd' => ['required', 'numeric', 'min:1.001', 'max:1001']
            ]);

            // so it is unpacked here
            $attributes['decimal_odd'] = $decimal_odd_array['odd'];

            $attributes['american_odd'] = self::decimalToAmerican($attributes['decimal_odd']);
        }

        // get user id from auth instead of request
        $attributes['user_id'] = auth()->id();

        // persist
        $bet = Bet::create($attributes);

        // attach categories
        $bet->categories()->attach($request['categories']);

        // redirect
        return redirect('/bets')->with('success', "You've created a new bet!");
    }


    /**
     * @param int|float $odd
     * 
     * @return int|float
     */
    public static function americanToDecimal($odd)
    {
        if ($odd > 0) {
            return ($odd / 100) + 1;
        } else {
            return (100 / abs($odd)) + 1;
        }
    }

    /**
     * @param int|float $odd
     * 
     * @return int|float
     */
    private static function decimalToAmerican($odd)
    {
        if ($odd >= 2) {
            return ($odd - 1) * 100;
        } else {
            return -100 / ($odd - 1);
        }
    }

    public function edit(Bet $bet)
    {
        return view('bets.edit', ['bet' => $bet]);
    }

    public function update(Request $request, Bet $bet)
    {
        // validate
        $attributes = $request->validate([
            'match' => ['required', 'string', 'max:100'],
            'bet_size' => ['required', 'numeric', 'min:0'],

            'sport' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['required', 'date_format:H:i'],
            'bookie' => ['required', 'string', 'max:255'],
            'bet_type' => ['required', 'string', 'max:255'],
            'bet_description' => ['nullable', 'string', 'max:255'],
            'bet_pick' => ['required', 'string', 'max:255'],
            'result' => ['nullable', Rule::in([0, 1, 2])],
            'cashout' => ['exclude_unless:result,2', 'required', 'numeric', 'min:0']
        ]);

        // get user prefence for odd type (american or decimal),
        // validate accordingly then store it into attributes array as respective odd_type value (american_odd) or (decimal_odd)
        // convert american to decimal or vice-versa depending on user odd_type preference
        $user_odd_type = auth()->user()->odd_type;

        if ($user_odd_type === 'american') {

            // validate method returns an array ['odd' => $odd]
            $american_odd_array = $request->validate([
                'odd' => ['required', 'numeric', 'min:-10000', 'max:100000']
            ]);

            // so it is unpacked here
            $attributes['american_odd'] = $american_odd_array['odd'];

            $attributes['decimal_odd'] = self::americanToDecimal($attributes['american_odd']);
        } elseif ($user_odd_type === 'decimal') {

            // validate method returns an array ['odd' => $odd]
            $decimal_odd_array = $request->validate([
                'odd' => ['required', 'numeric', 'min:1.001', 'max:1001']
            ]);

            // so it is unpacked here
            $attributes['decimal_odd'] = $decimal_odd_array['odd'];

            $attributes['american_odd'] = self::decimalToAmerican($attributes['decimal_odd']);
        }

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
