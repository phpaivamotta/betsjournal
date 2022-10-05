<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Bet extends Model
{
    use HasFactory;

    protected $guarded = [];

    private function decimalToAmerican()
    {

    }

    // private function americanToDecimal()
    // {
    //     if ($this->odds > 0) {
    //         return ($this->odds / 100) + 1;
    //     } else {
    //         return (100 / abs($this->odds)) + 1;
    //     }
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
