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
     * Get value bets.
     * 
     * Get a list of value bets for your selected bookie region and min. over value.
     * 
     * @queryParam sport string required The selected sport market from list of available sports which can be obtained from the /value-bets/sports endpoint. Example: soccer_italy_serie_a
     * 
     * @queryParam regions required Comma-separated list of regions to filter by. Example: us,uk
     * 
     * @queryParam overValue float|int required Min. over value percentage to filter value bets by. Example: 20
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
    public function __invoke(Request $request)
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
