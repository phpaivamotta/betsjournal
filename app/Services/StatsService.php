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
            if ($bet->result === 1) {
                // note: this is the profit of each winning bet
                $profitArray[] = $bet->payout() - $bet->bet_size;
            } else if ($bet->result === 0) {
                // note: this is the loss of each losing bet
                $profitArray[] = - ((float) $bet->bet_size);
            } else if ($bet->result === 2) {
                // note: this is the profit/loss of each cashout bet
                $profitArray[] = $bet->cashout - $bet->bet_size;
            }
        }

        // cummulatively sum the results
        // i.e., each array value will be the sum of all bets that came before it
        // e.g., if bet1 = $100, bet2 = $50, and bet3 = -$30
        // then the $netProfitArr = [1 => 100, 2 => (100 + 50), 3 => (100 + 50 -30)]
        // which gives an array with the total profit at a given bet number
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
        $resultOptions = [
            'wins' => isset($resultGroups[1]) ? $resultGroups[1] : null,
            'losses' => isset($resultGroups[0]) ? $resultGroups[0] : null,
            'na' => isset($resultGroups[null]) ? $resultGroups[null] : null,
            'co' => isset($resultGroups[2]) ? $resultGroups[2] : null,
        ];

        // this is the order in which the bet results are returned in $resultGroups
        $resultOrder = [
            'wins',
            'losses',
            'na',
            'co'
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
     * 
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
            isset($betResults[null]) ? $betResults[null] : 0,
            isset($betResults[2]) ? $betResults[2] : 0
        ];
    }

    /**
     * calculate net gains, without discounting the losses 
     */
    public function totalGains(): float
    {
        $winningBetsGain = $this->bets
            ->where('result', '1')
            ->map(fn ($bet) => $bet->payout() - $bet->bet_size)
            ->sum();

        $coBetsGain = $this->bets
            ->where('result', '2')
            ->filter(function ($coBet) {
                return $coBet->cashout > $coBet->bet_size;
            })
            ->sum(function ($coBet) {
                return $coBet->cashout - $coBet->bet_size;
            });

        return round($winningBetsGain + $coBetsGain, 2);
    }

    /**
     * calculate net losses, without adding the wins 
     */
    public function totalLosses(): float
    {
        $losingBetsLosses = $this->bets
            ->where('result', '0')
            ->pluck('bet_size')
            ->sum();

        $coBetsLosses = $this->bets
            ->where('result', '2')
            ->filter(function ($coBet) {
                return $coBet->cashout < $coBet->bet_size;
            })
            ->sum(function ($coBet) {
                return $coBet->cashout - $coBet->bet_size;
            });

        return $losingBetsLosses + $coBetsLosses;
    }

    public function biggestPayout(): ?float
    {
        $maxWinPayout = $this->bets->where('result', '1')
            ->map(fn ($bet) => $bet->payout())
            ->max();

        $maxCOPayout = $this->bets
            ->where('result', '2')
            ->pluck('cashout')
            ->max();

        return max($maxWinPayout, $maxCOPayout);
    }

    public function biggestLoss(): ?float
    {
        $losingBetsBiggestLoss = $this->bets->where('result', '0')->max('bet_size');

        $coBetsBiggestLoss = $this->bets
            ->where('result', '2')
            ->filter(function ($coBet) {
                return $coBet->cashout < $coBet->bet_size;
            })
            ->max(function ($coBet) {
                return abs($coBet->cashout - $coBet->bet_size);
            });

        return max($losingBetsBiggestLoss, $coBetsBiggestLoss);
    }
}
