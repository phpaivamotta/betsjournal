<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ValueBetsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ValueBets extends Controller
{
    public function __invoke(Request $request)
    {
        $valueBetsService = new ValueBetsService();
        $sports = $valueBetsService->getSports();

        $request['regions'] = explode(',', $request['regions']);

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
