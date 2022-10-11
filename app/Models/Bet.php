<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Bet extends Model
{
    use HasFactory;

    protected $guarded = [];

    // array of attributes that are optional to display (both in DB and index view)
    public static $optional_attributes = ['bookie', 'sport', 'match_date', 'match_time', 'bet_type', 'bet_pick', 'bet_description'];

    /**
     * Set match_time format to H:i so that it is consistent with input time
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    // protected function matchTime(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => Carbon::createFromFormat('H:i:s', $value)->format('H:i'),
    //     );
    // }

    /**
     * Set match_time format to H:i so that it is consistent with input time
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    // protected function matchDate(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => Carbon::createFromFormat('Y-m-d', $value)->format('m-d-Y'),
    //     );
    // }

    public function payoff()
    {
        return round($this->decimal_odd * $this->bet_size, 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
