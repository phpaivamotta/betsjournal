<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class StatsService
{
    public Collection $bets;

    public function __construct(Collection $bets)
    {
        $this->bets = $bets;
    }

    public function averageOdds(string $oddFormat): float
    {
        if ($oddFormat === 'american') {
            return number_format($this->bets->avg('american_odd'), 3);
        } elseif ($oddFormat === 'decimal') {
            return number_format($this->bets->avg('decimal_odd'), 3);
        }
    }

    public function impliedProbability(): ?float
    {
        $avgDecimalOdds = $this->averageOdds('decimal');

        // check if average odds are not zero (which means there are no bets)
        // otherwise a division by zero error would be thrown
        if ($avgDecimalOdds) {
            return number_format(100 * (1 / $avgDecimalOdds), 2);
        } else {
            return null;
        }
    }

    /**
     * calculates net profit as the number of bets increases
     */
    public function netProfit(): array
    {
        // sort bets from first through last
        $bets = $this->bets->sortBy([
            ['match_date', 'asc'],
            ['match_time', 'asc'],
        ]);

        // calculate how much each bet won/lost
        $profitArray = [];
        foreach ($bets as $bet) {
            // need to add cashout
            if ($bet->result === 1) {
                // note: this is the profit of each winning bet
                $profitArray[] = $bet->payout() - $bet->bet_size;
            } else if ($bet->result === 0) {
                $profitArray[] = - ((float) $bet->bet_size);
            }
        }

        // cummulatively sum the results
        // i.e., each array value will be the sum of all bets that came before it
        // note: I added a 1 to the array index so that I could use them on the graph's x-axis
        $netProfitArr = [];
        for ($i = 0; $i < count($profitArray); $i++) {
            if ($i > 0) {
                $netProfitArr[($i + 1)] = $netProfitArr[$i] + $profitArray[$i];
            } else {
                $netProfitArr[($i + 1)] = $profitArray[$i];
            }
        }

        // round to 2 decimal places and return
        return array_map(function ($profit) {
            return round($profit, 2);
        }, $netProfitArr);
    }

    /**
     * calculate result count distribution in the prob. range
     */
    public function resultCountProbabilityRange(): array
    {
        // group bet results
        $resultGroups = $this->bets
            ->groupBy('result')
            ->map(
                fn ($resultBin) => $resultBin
                    ->map(
                        fn ($bet) => floor(((float) rtrim($bet->impliedProbability(), "%")) / 10)
                    )->countBy()
            )->toArray();

        // get results that are present in the bets
        // need to add cashout
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

        $resultCountProbabilityRangeArray = [];
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

            $resultCountProbabilityRangeArray[$resultOrder[$count]] = $oneResultArr;
            $count++;
        }
        return $resultCountProbabilityRangeArray;
    }

    /**
     * count results win/loss/na 
     * need to add cashout
     */
    public function resultsCount(): array
    {
        // count each result
        $betResults = $this->bets
            ->groupBy('result')
            ->map(fn ($betResults) => $betResults->count())
            ->toArray();

        // check if result exists and return order in which will be used in view
        return [
            isset($betResults[1]) ? $betResults[1] : 0,
            isset($betResults[0]) ? $betResults[0] : 0,
            isset($betResults[null]) ? $betResults[null] : 0
        ];
    }
}
