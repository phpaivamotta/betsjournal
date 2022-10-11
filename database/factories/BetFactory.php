<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bet>
 */
class BetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'sport' => fake()->word(),
            'match' => fake()->sentence(),
            'match_date' => fake()->date(),
            'match_time' => fake()->time('H:i'),
            'bookie' => fake()->word(),
            'bet_type' => fake()->word(),
            'bet_description' => fake()->sentence(),
            'bet_pick' => fake()->word(),
            'bet_size' => fake()->randomFloat(2, 100, 300),
            'decimal_odd' => fake()->randomFloat(3, 2, 10),
            'american_odd' => fake()->randomFloat(3, -500, 500),
            'result' => fake()->boolean(),
        ];
    }
}
