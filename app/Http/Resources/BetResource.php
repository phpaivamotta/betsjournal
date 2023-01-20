<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sport' => $this->sport,
            'match' => $this->match,
            'match_date' => $this->match_date,
            'match_time' => $this->match_time,
            'bookie' => $this->bookie,
            'bet_type' => $this->bet_type,
            'bet_description' => $this->bet_description,
            'bet_pick' => $this->bet_pick,
            'bet_size' => $this->bet_size,
            'decimal_odd' => $this->decimal_odd,
            'american_odd' => $this->american_odd,
            'result' => $this->result,
            'cashout' => $this->cashout,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
