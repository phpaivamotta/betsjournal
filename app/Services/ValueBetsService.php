<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ValueBetsService
{
    public function getSports()
    {
        // cache in season sports for a day (86,400 seconds)
        $sports = Cache::remember('sports', 86400, function () {
            return Http::retry(3, 10)->get(
                'https://api.the-odds-api.com/v4/sports/?apiKey=' . config('services.the-odds-api.key')
            )->throw()->json();
        });

        // return array with just the sports names
        return array_map(function ($sport) {
            return $sport['key'];
        }, $sports);
    }

    // get the odds from the-odds-api
    public function requestOdds(array $regions, string $sport)
    {
        // join selected regions in coma separated string
        $regionsQStr = join(',', $regions);

        // call odds API endpoint and return response
        // note: for some reason, api call only works when I retry
        return Http::retry(3, 50)->get(
            'https://api.the-odds-api.com/v4/sports/' . $sport .
                '/odds/?apiKey=' . config('services.the-odds-api.key') .
                '&regions=' . $regionsQStr .
                '&oddsFormat=decimal'
        )->throw()->json();
    }

    // returns an array with all matches and its bookies' money-line odds
    private static function buildMatchesArray(array $matches): array
    {
        $matchesArr = [];
        foreach ($matches as $match) {

            if (!$match['bookmakers']) {
                continue;
            }

            $matchArr['home_team'] = $match['home_team'];
            $matchArr['away_team'] = $match['away_team'];
            $matchArr['sport'] = $match['sport_title'];
            $matchArr['commence_time'] = $match['commence_time'];
            $matchArr['num_bookies_analyzed'] = [];
            $matchArr['home_team_avg_odd'] = 0;
            $matchArr['away_team_avg_odd'] = 0;
            $matchArr['draw_avg_odd'] = 0;
            $matchArr['home_team_odds'] = [];
            $matchArr['away_team_odds'] = [];
            $matchArr['draw_odds'] = [];

            foreach ($match['bookmakers'] as $bookmaker) {
                foreach ($bookmaker['markets'][0]['outcomes'] as $outcome) {
                    if ($outcome['name'] === $match['home_team']) {
                        $matchArr['home_team_odds'] += [$bookmaker['title'] => $outcome['price']];
                    } else if ($outcome['name'] === $match['away_team']) {
                        $matchArr['away_team_odds'] += [$bookmaker['title'] => $outcome['price']];
                    } else if (strtolower($outcome['name']) === 'draw') {
                        $matchArr['draw_odds'] += [$bookmaker['title'] => $outcome['price']];
                    }
                }
            }

            if (count($matchArr['home_team_odds']) > 0) {
                $matchArr['home_team_avg_odd'] = array_sum(array_values($matchArr['home_team_odds'])) / count($matchArr['home_team_odds']);
                $matchArr['num_bookies_analyzed']['home_team'] = count($matchArr['home_team_odds']);
            } else {
                $matchArr['home_team_avg_odd'] = 0;
                $matchArr['num_bookies_analyzed']['home_team'] = 0;
            }

            if (count($matchArr['away_team_odds']) > 0) {
                $matchArr['away_team_avg_odd'] = array_sum(array_values($matchArr['away_team_odds'])) / count($matchArr['away_team_odds']);
                $matchArr['num_bookies_analyzed']['away_team'] = count($matchArr['away_team_odds']);
            } else {
                $matchArr['away_team_avg_odd'] = 0;
                $matchArr['num_bookies_analyzed']['away_team'] = 0;
            }

            if (count($matchArr['draw_odds']) > 0) {
                $matchArr['draw_avg_odd'] = array_sum(array_values($matchArr['draw_odds'])) / count($matchArr['draw_odds']);
                $matchArr['num_bookies_analyzed']['draw'] = count($matchArr['draw_odds']);
            } else {
                $matchArr['draw_avg_odd'] = 0;
                $matchArr['num_bookies_analyzed']['draw'] = 0;
            }

            array_push($matchesArr, $matchArr);
        }

        return $matchesArr;
    }

    // get value bets opportunities for each bet
    public function getValueBets(array $regions, string $sport, float $minOverValue): array
    {
        // get the odds for the chosen API parameters
        $matches = $this->requestOdds($regions, $sport);

        $matchesArray = self::buildMatchesArray($matches);

        $valueBets = [];
        foreach ($matchesArray as $match) {
            $valueBet['home_team'] = $match['home_team'];
            $valueBet['away_team'] = $match['away_team'];
            $valueBet['sport'] = $match['sport'];
            $valueBet['num_bookies_analyzed'] = $match['num_bookies_analyzed'];
            $valueBet['commence_time'] = $match['commence_time'];
            $valueBet['value_bets'] = [];

            foreach ($match['home_team_odds'] as $bookie => $odd) {
                if ($odd > $match['home_team_avg_odd'] && (($odd / $match['home_team_avg_odd']) - 1) >= $minOverValue) {
                    $valueBet['value_bets']['home_team'][$bookie] = [
                        'over_value' => round(($odd / $match['home_team_avg_odd']) - 1, 4),
                        'money_line' => $odd
                    ];
                }
            }

            foreach ($match['away_team_odds'] as $bookie => $odd) {
                if ($odd > $match['away_team_avg_odd'] && (($odd / $match['away_team_avg_odd']) - 1) >= $minOverValue) {
                    $valueBet['value_bets']['away_team'][$bookie] = [
                        'over_value' => round(($odd / $match['away_team_avg_odd']) - 1, 4),
                        'money_line' => $odd
                    ];
                }
            }

            foreach ($match['draw_odds'] as $bookie => $odd) {
                if ($odd > $match['draw_avg_odd'] && (($odd / $match['draw_avg_odd']) - 1) >= $minOverValue) {
                    $valueBet['value_bets']['draw'][$bookie] = [
                        'over_value' => round(($odd / $match['draw_avg_odd']) - 1, 4),
                        'money_line' => $odd
                    ];
                }
            }

            if ($valueBet['value_bets']) {
                array_push($valueBets, $valueBet);
            }
        }

        return $valueBets;
    }
}
