<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'title' => fake()->words(3, true),
            'type' => fake()->randomElement(['puzzle', 'board', 'card']),
            'status' => 'draft',
            'short_description' => fake()->sentence(),
            'rules_md' => fake()->paragraphs(3, true),
            'options_json' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }
}
