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

    private function decimalToAmerican()
    {
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
