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
                        $matchArr['home_team_odds'] += [$bookmaker['key'] => $outcome['price']];
                    } else if ($outcome['name'] === $match['away_team']) {
                        $matchArr['away_team_odds'] += [$bookmaker['key'] => $outcome['price']];
                    } else if (strtolower($outcome['name']) === 'draw') {
                        $matchArr['draw_odds'] += [$bookmaker['key'] => $outcome['price']];
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

    public const BOOKIE_LINKS = [
        // AU Bookmakers
        'betfair' => [
            'name' => 'Betfair Exchange',
            'link' => 'https://www.betfair.com.au/exchange/plus/'
        ],
        'bluebet' => [
            'name' => 'BlueBet',
            'link' => 'https://www.bluebet.com.au/'
        ],
        'ladbrokes' => [
            'name' => 'Ladbrokes',
            'link' => 'https://www.ladbrokes.com.au/'
        ],
        'neds' => [
            'name' => 'Neds',
            'link' => 'https://www.neds.com.au/sports'
        ],
        'pointsbetau' => [
            'name' => 'PointsBet (AU)',
            'link' => 'https://pointsbet.com.au/'
        ],
        'sportsbet' => [
            'name' => 'SportsBet',
            'link' => 'https://www.sportsbet.com.au/'
        ],
        'tab' => [
            'name' => 'TAB',
            'link' => 'https://tab.com.au/'
        ],
        'topsport' => [
            'name' => 'TopSport',
            'link' => 'https://www.topsport.com.au/'
        ],
        'unibet' => [
            'name' => 'Unibet',
            'link' => 'http://unibet.com.au/'
        ],
        // US Bookmakers
        'barstool' => [
            'name' => 'Barstool Sportsbook',
            'link' => 'https://www.barstoolsportsbook.com/'
        ],
        'betonlineag' => [
            'name' => 'BetOnline.ag',
            'link' => 'https://www.betonline.ag/'
        ],
        'betfair' => [
            'name' => 'Betfair Exchange',
            'link' => 'https://www.betfair.com/exchange/plus/'
        ],
        'betmgm' => [
            'name' => 'BetMGM',
            'link' => 'https://sports.nj.betmgm.com/en/sports'
        ],
        'betrivers' => [
            'name' => 'BetRivers',
            'link' => 'https://www.betrivers.com/'
        ],
        'betus' => [
            'name' => 'BetUS',
            'link' => 'https://www.betus.com.pa/'
        ],
        'bovada' => [
            'name' => 'Bovada',
            'link' => 'https://www.bovada.lv/'
        ],
        'circasports' => [
            'name' => 'Circa Sports',
            'link' => 'https://co.circasports.com/'
        ],
        'draftkings' => [
            'name' => 'DraftKings',
            'link' => 'https://draftkings.com/'
        ],
        'fanduel' => [
            'name' => 'FanDuel',
            'link' => 'https://sportsbook.fanduel.com/sports'
        ],
        'foxbet' => [
            'name' => 'FOX Bet',
            'link' => 'https://www.foxbet.com/'
        ],
        'gtbets' => [
            'name' => 'GTbets',
            'link' => 'https://www.gtbets.eu/'
        ],
        'intertops' => [
            'name' => 'Intertops (Everygame)',
            'link' => 'https://everygame.eu/'
        ],
        'lowvig' => [
            'name' => 'LowVig.ag',
            'link' => 'https://www.lowvig.ag/'
        ],
        'mybookieag' => [
            'name' => 'MyBookie.ag',
            'link' => 'https://mybookie.ag/'
        ],
        'pointsbetus' => [
            'name' => 'PointsBet (US)',
            'link' => 'https://nj.pointsbet.com/sports'
        ],
        'sugarhouse' => [
            'name' => 'SugarHouse',
            'link' => 'https://www.playsugarhouse.com/'
        ],
        'superbook' => [
            'name' => 'SuperBook',
            'link' => 'https://co.superbook.com/sports'
        ],
        'twinspires' => [
            'name' => 'TwinSpires',
            'link' => 'https://www.twinspires.com/'
        ],
        'unibet_us' => [
            'name' => 'Unibet',
            'link' => 'https://nj.unibet.com/'
        ],
        'williamhill_us' => [
            'name' => 'William Hill (US)',
            'link' => 'https://www.williamhill.com/us/nj/bet/'
        ],
        'wynnbet' => [
            'name' => 'WynnBET',
            'link' => 'https://www.wynnbet.com/'
        ],
        // UK Bookmakers
        'sport888' => [
            'name' => '888sport',
            'link' => 'https://www.888sport.com/'
        ],
        'betfair' => [
            'name' => 'Betfair Exchange',
            'link' => 'https://www.betfair.com/exchange/plus/'
        ],
        'betvictor' => [
            'name' => 'Bet Victor',
            'link' => 'https://www.betvictor.com/'
        ],
        'betway' => [
            'name' => 'Betway',
            'link' => 'https://betway.com/en/sports'
        ],
        'boylesports' => [
            'name' => 'BoyleSports',
            'link' => 'https://boylesports.com/sports/'
        ],
        'casumo' => [
            'name' => 'Casumo',
            'link' => 'https://casumo.com'
        ],
        'coral' => [
            'name' => 'Coral',
            'link' => 'https://sports.coral.co.uk/'
        ],
        'ladbrokes' => [
            'name' => 'Ladbrokes',
            'link' => 'https://www.ladbrokes.com/'
        ],
        'leovegas' => [
            'name' => 'LeoVegas',
            'link' => 'https://www.leovegas.com/en-gb/'
        ],
        'livescorebet' => [
            'name' => 'LiveScore Bet',
            'link' => 'https://www.livescorebet.com/'
        ],
        'matchbook' => [
            'name' => 'Matchbook',
            'link' => 'https://www.matchbook.com/'
        ],
        'mrgreen' => [
            'name' => 'Mr Green',
            'link' => 'https://www.mrgreen.com/'
        ],
        'paddypower' => [
            'name' => 'Paddy Power',
            'link' => 'https://www.paddypower.com/'
        ],
        'skybet' => [
            'name' => 'Sky Bet',
            'link' => 'https://m.skybet.com/'
        ],
        'unibet_uk' => [
            'name' => 'Unibet',
            'link' => 'https://www.unibet.co.uk/'
        ],
        'virginbet' => [
            'name' => 'Virgin Bet',
            'link' => 'https://www.virginbet.com/'
        ],
        'williamhill' => [
            'name' => 'William Hill (UK)',
            'link' => 'https://www.williamhill.com/'
        ],
        // EU Bookmakers
        'onexbet' => [
            'name' => '1xBet',
            'link' => 'https://1xbet.com/'
        ],
        'sport888' => [
            'name' => '888sport',
            'link' => 'https://www.888sport.com/'
        ],
        'betclic' => [
            'name' => 'Betclic',
            'link' => 'https://www.betclic.com/'
        ],
        'betfair' => [
            'name' => 'Betfair Exchange',
            'link' => 'https://www.betfair.com/exchange/plus/'
        ],
        'betonlineag' => [
            'name' => 'BetOnline.ag',
            'link' => 'https://www.betonline.ag/'
        ],
        'betsson' => [
            'name' => 'Betsson',
            'link' => 'https://betsson.com/'
        ],
        'betvictor' => [
            'name' => 'Bet Victor',
            'link' => 'https://www.betvictor.com/'
        ],
        'intertops' => [
            'name' => 'Intertops (Everygame)',
            'link' => 'https://everygame.eu/'
        ],
        'marathonbet' => [
            'name' => 'Marathon Bet',
            'link' => 'https://www.marathonbet.co.uk/en/'
        ],
        'matchbook' => [
            'name' => 'Matchbook',
            'link' => 'https://www.matchbook.com/'
        ],
        'mybookieag' => [
            'name' => 'MyBookie.ag',
            'link' => 'https://mybookie.ag/'
        ],
        'pinnacle' => [
            'name' => 'Pinnacle',
            'link' => 'https://www.pinnacle.com/'
        ],
        'unibet_eu' => [
            'name' => 'Unibet',
            'link' => 'https://www.unibet.nl/'
        ],
        'williamhill' => [
            'name' => 'William Hill',
            'link' => 'https://www.williamhill.com/'
        ],
    ];
}
