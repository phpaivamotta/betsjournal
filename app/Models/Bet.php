<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use NumberFormatter;

class Bet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilter($query, string $search, ?bool $win, ?bool $loss, ?bool $na)
    {
        $query->where(function ($query) use($search) {

            $query->where('match', 'like', '%' . $search . '%')
                ->orWhere('bookie', 'like', '%' . $search . '%')
                ->orWhere('sport', 'like', '%' . $search . '%')
                ->orWhere('bet_type', 'like', '%' . $search . '%')
                ->orWhere('bet_pick', 'like', '%' . $search . '%')
                ->orWhere('bet_description', 'like', '%' . $search . '%');

        })->where(function ($query) use($win, $loss, $na) {

            $query->when($win, function ($query) {

                $query->where('result', true);

            })->when($loss, function ($query) {
                
                $query->orWhere('result', false);

            })->when($na, function ($query) {
                
                $query->orWhere('result', null);

            });

        });
    }

    public function payout()
    {
        return $this->decimal_odd * $this->bet_size;
    }

    public function impliedProbability()
    {
        $impliedProbability = (1 / $this->decimal_odd);

        return (new NumberFormatter('en_US', NumberFormatter::PERCENT))
            ->format($impliedProbability);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
