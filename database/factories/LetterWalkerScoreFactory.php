<?php

namespace Database\Factories;

use App\Models\LetterWalkerScore;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LetterWalkerScore>
 */
class LetterWalkerScoreFactory extends Factory
{
    protected $model = LetterWalkerScore::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'player_name' => fake()->name(),
            'score' => fake()->numberBetween(0, 500),
            'moves' => fake()->numberBetween(1, 100),
            'words_found' => 1,
            'puzzle_number' => fake()->numberBetween(1, 365),
            'date_played' => now()->toDateString(),
        ];
    }
}
