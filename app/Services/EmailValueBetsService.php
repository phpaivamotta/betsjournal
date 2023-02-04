<?php

namespace App\Services;

use App\Mail\ValueBetsMail;
use App\Models\Subscriber;
use App\Services\ValueBetsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailValueBetsService
{
    public function sendValueBetsEmail(Subscriber $subscriber = null)
    {
        $valueBetsService = new ValueBetsService;

        $maxNumValueBets = 5;
        $maxNumApiCalls = 3;
        $minOverValue = 0.1; // 0.1 = 10%
        $sports = collect($valueBetsService->getSports());

        for ($i = 0; $i < $maxNumApiCalls; $i++) {
            // get random in-season sport
            $sport = $sports->random();

            // get random region
            $regions = [collect(['us', 'uk', 'eu', 'au'])->random()];

            // get value bets
            $valueBets = $valueBetsService->getValueBets(
                $regions,
                $sport,
                $minOverValue
            );

            // if $valueBets array contains data, quit loop
            if ($valueBets) {
                break;
            }
        }

        // check if $valueBets array contains data
        if ($valueBets) {
            // get the first $maxNumValueBets value bets
            $count = 0;
            $matchesArr = [];
            foreach ($valueBets as $match) {
                // get match info
                $matchArr = [];
                $matchArr['home_team'] = $match['home_team'];
                $matchArr['away_team'] = $match['away_team'];
                $matchArr['sport'] = $match['sport'];
                $matchArr['num_bookies_analyzed'] = $match['num_bookies_analyzed'];
                $matchArr['commence_time'] = $match['commence_time'];

                // build value bets
                foreach ($match['value_bets'] as $outcome => $bookies) {
                    foreach ($bookies as $bookieName => $bookieStats) {
                        // store value bets
                        $matchArr['value_bets'][$outcome][$bookieName] = $bookieStats;

                        // quit if over $maxNumValueBets value bets have been recorded
                        $count++;
                        if ($count >= $maxNumValueBets) {
                            // put current value bets match inside the matches array
                            // need to do this here since the wanted number of value bets has been found and we are breaking out of the loop 
                            array_push($matchesArr, $matchArr);

                            // break out of all loops (3 layers) 
                            break 3;
                        }
                    }
                }

                // put current value bets match inside the matches array
                array_push($matchesArr, $matchArr);
            }

            if (isset($subscriber)) {
                Mail::to($subscriber->email)->send(
                    new ValueBetsMail($matchesArr, $subscriber->id)
                );
            } else {
                $subscribers = Subscriber::where('subscribed', true)->get();
                foreach ($subscribers as $subscriber) {
                    Mail::to($subscriber->email)->send(
                        new ValueBetsMail($matchesArr, $subscriber->id)
                    );
                }
            }
            
        } else {
            Log::info('No value bets found at ' . now('America/sao_paulo'));
        }
    }
}
