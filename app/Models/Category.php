<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const COLORS = [
        'blue',
        'indigo',
        'brown',
        'black',
        'yellow',
    ];

    public function bets()
    {
        return $this->belongsToMany(Bet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
