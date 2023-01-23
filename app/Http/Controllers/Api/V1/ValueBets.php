<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ValueBetsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Value Bets
 * 
 * Get value bet opportunities.
 */
class ValueBets extends Controller
{

	/**
	 * Sports list
	 * 
	 * Get list of available sports for which there are value bets.
	 * 
	 * @response [
	 *     "americanfootball_nfl",
	 *     "americanfootball_nfl_super_bowl_winner",
	 *     "aussierules_afl",
	 *     "baseball_mlb_world_series_winner",
	 *     "basketball_euroleague",
	 *     "basketball_nba",
	 *     "basketball_nba_championship_winner",
	 *     "basketball_ncaab",
	 *     "cricket_big_bash",
	 *     "cricket_odi",
	 *     "golf_masters_tournament_winner",
	 *     "golf_pga_championship_winner",
	 *     "golf_the_open_championship_winner",
	 *     "golf_us_open_winner",
	 *     "icehockey_nhl",
	 *     "icehockey_nhl_championship_winner",
	 *     "icehockey_sweden_allsvenskan",
	 *     "icehockey_sweden_hockey_league",
	 *     "mma_mixed_martial_arts",
	 *     "rugbyleague_nrl",
	 *     "soccer_argentina_primera_division",
	 *     "soccer_australia_aleague",
	 *     "soccer_belgium_first_div",
	 *     "soccer_chile_campeonato",
	 *     "soccer_denmark_superliga",
	 *     "soccer_efl_champ",
	 *     "soccer_england_efl_cup",
	 *     "soccer_england_league1",
	 *     "soccer_england_league2",
	 *     "soccer_epl",
	 *     "soccer_fa_cup",
	 *     "soccer_france_ligue_one",
	 *     "soccer_france_ligue_two",
	 *     "soccer_germany_bundesliga",
	 *     "soccer_germany_bundesliga2",
	 *     "soccer_italy_serie_a",
	 *     "soccer_italy_serie_b",
	 *     "soccer_league_of_ireland",
	 *     "soccer_mexico_ligamx",
	 *     "soccer_netherlands_eredivisie",
	 *     "soccer_norway_eliteserien",
	 *     "soccer_poland_ekstraklasa",
	 *     "soccer_portugal_primeira_liga",
	 *     "soccer_spain_la_liga",
	 *     "soccer_spain_segunda_division",
	 *     "soccer_spl",
	 *     "soccer_sweden_allsvenskan",
	 *     "soccer_sweden_superettan",
	 *     "soccer_switzerland_superleague",
	 *     "soccer_turkey_super_league",
	 *     "soccer_uefa_champs_league",
	 *     "soccer_uefa_europa_conference_league",
	 *     "soccer_uefa_europa_league",
	 *     "tennis_atp_aus_open_singles",
	 *     "tennis_wta_aus_open_singles"
	 * ]
	 */
	public function sports()
	{
		return (new ValueBetsService)->getSports();
	}

    /**
     * Get value bets.
     * 
     * Get a list of value bets for your selected bookie region and min. over value.
     * 
     * @queryParam sport string required The selected sport market from list of available sports which can be obtained from the /value-bets/sports endpoint. Example: soccer_italy_serie_a
     * 
     * @queryParam regions required Comma-separated list of bookie regions to filter by. Example: us,uk
     * 
     * @queryParam overValue float required Min. over value percentage to filter value bets by. Example: 20
     * 
     * @response [
	 *  {
	 *	"home_team": "Hellas Verona FC",
	 *	"away_team": "Lecce",
	 *	"sport": "Serie A - Italy",
	 *	"num_bookies_analyzed": {
	 *		"home_team": 32,
	 *		"away_team": 32,
	 *		"draw": 32
	 *	},
	 *	"commence_time": "2023-01-21T14:00:00Z",
	 *	"value_bets": {
	 *		"home_team": {
	 *			"Matchbook": {
	 *				"over_value": 0.0576,
	 *				"money_line": 2.52
	 *			}
	 *		},
	 *		"away_team": {
	 *			"Bovada": {
	 *				"over_value": 0.07,
	 *				"money_line": 3.5
	 *			},
	 *			"Betfair": {
	 *				"over_value": 0.0547,
	 *				"money_line": 3.45
	 *			},
	 *			"Matchbook": {
	 *				"over_value": 0.0547,
	 *				"money_line": 3.45
	 *			}
	 *		}
	 *	}
	 *},
	 *{
	 *	"home_team": "Salernitana",
	 *	"away_team": "Napoli",
	 *	"sport": "Serie A - Italy",
	 *	"num_bookies_analyzed": {
	 *		"home_team": 33,
	 *		"away_team": 33,
	 *		"draw": 33
	 *	},
	 *	"commence_time": "2023-01-21T17:00:00Z",
	 *	"value_bets": {
	 *		"home_team": {
	 *			"Ladbrokes": {
	 *				"over_value": 0.066,
	 *				"money_line": 10.5
	 *			},
	 *			"Coral": {
	 *				"over_value": 0.1168,
	 *				"money_line": 11
	 *			},
	 *			"Bet Victor": {
	 *				"over_value": 0.1168,
	 *				"money_line": 11
	 *			},
	 *			"Sky Bet": {
	 *				"over_value": 0.1168,
	 *				"money_line": 11
	 *			},
	 *			"Betfair": {
	 *				"over_value": 0.1168,
	 *				"money_line": 11
	 *			},
	 *			"William Hill (US)": {
	 *				"over_value": 0.066,
	 *				"money_line": 10.5
	 *			},
	 *			"Matchbook": {
	 *				"over_value": 0.1168,
	 *				"money_line": 11
	 *			}
	 *		},
	 *		"draw": {
	 *			"Betfair": {
	 *				"over_value": 0.0901,
	 *				"money_line": 6.4
	 *			},
	 *			"SugarHouse": {
	 *				"over_value": 0.0645,
	 *				"money_line": 6.25
	 *			},
	 *			"Matchbook": {
	 *				"over_value": 0.0901,
	 *				"money_line": 6.4
	 *			},
	 *			"LowVig.ag": {
	 *				"over_value": 0.0815,
	 *				"money_line": 6.35
	 *			},
	 *			"BetOnline.ag": {
	 *				"over_value": 0.0815,
	 *				"money_line": 6.35
	 *			}
	 *		}
	 *	}
	 *}
     *]
     */
    public function index(Request $request)
    {
        $valueBetsService = new ValueBetsService();
        $sports = $valueBetsService->getSports();

        $request['regions'] = explode(',', $request['regions']);

        // Query parameters
        $request->validate([
            'sport' => ['required', Rule::in($sports)],
            'regions' => ['required', 'array', 'max:4'],
            'regions.*' => [Rule::in(['us', 'uk', 'eu', 'au']), 'distinct'],
            'overValue' => ['required', 'numeric', 'between:1,100'],
        ]);

        // transform over value to decimal percent
        $overValueTransformed = $request['overValue'] / 100;

        $valueBets = $valueBetsService
            ->getValueBets(
                $request['regions'],
                $request['sport'],
                $overValueTransformed
            );

        return $valueBets;
    }
}
