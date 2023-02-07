<?php

namespace App\Exports;

use App\Models\Bet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserBetsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // I am being specific about the columns I am getting to remove the id and user_id columns, for security
        return Bet::where('user_id', auth()->id())
            ->orderBy('match_date', 'desc')
            ->orderBy('match_time', 'desc')
            ->get([
                'sport',
                'match',
                'match_date',
                'match_time',
                'bookie',
                'bet_type',
                'bet_description',
                'bet_pick',
                'bet_size',
                'decimal_odd',
                'american_odd',
                'result',
                'cashout',
                'created_at',
                'updated_at'
            ])
            ->map(function ($bet) {
                if ($bet->result === 0) {
                    $bet->result = 'loss';
                } elseif ($bet->result === 1) {
                    $bet->result = 'win';
                } elseif ($bet->result === 2) {
                    $bet->result = 'cashout';
                } elseif ($bet->result === null) {
                    $bet->result = 'N/A';
                }

                return $bet;
            });
    }

    public function headings(): array
    {
        return [
            'sport',
            'match',
            'match_date',
            'match_time',
            'bookie',
            'bet_type',
            'bet_description',
            'bet_pick',
            'bet_size',
            'decimal_odd',
            'american_odd',
            'result',
            'cashout',
            'created_at',
            'updated_at'
        ];
    }
}
