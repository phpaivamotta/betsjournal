<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Bet extends Model
{
    use HasFactory;

    protected $guarded = [];

    // array of attributes that are optional to display (both in DB and index view)
    public static $optional_attributes = ['bookie', 'sport', 'match_date', 'match_time', 'bet_type', 'bet_pick', 'bet_description'];

    public function scopeFilter($query, string $search, ?bool $win_checkbox, ?bool $loss_checkbox, ?bool $na_checkbox)
    {
        $query->where(function ($query) use($search) {

            $query->where('match', 'like', '%' . $search . '%')
                ->orWhere('bookie', 'like', '%' . $search . '%')
                ->orWhere('sport', 'like', '%' . $search . '%')
                ->orWhere('bet_type', 'like', '%' . $search . '%')
                ->orWhere('bet_pick', 'like', '%' . $search . '%')
                ->orWhere('bet_description', 'like', '%' . $search . '%');

        })->where(function ($query) use($win_checkbox, $loss_checkbox, $na_checkbox) {

            $query->when($win_checkbox, function ($query) {

                $query->where('result', true);

            })->when($loss_checkbox, function ($query) {
                
                $query->orWhere('result', false);

            })->when($na_checkbox, function ($query) {
                
                $query->orWhere('result', null);

            });

        });
    }

    public function payoff()
    {
        return round($this->decimal_odd * $this->bet_size, 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
