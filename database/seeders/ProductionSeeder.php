<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed essential production data.
     * Idempotent - can be run multiple times safely.
     */
    public function run(): void
    {
        $this->seedGames();
    }

    /**
     * Seed core browser games.
     */
    private function seedGames(): void
    {
        $games = config('games.catalog', []);

        foreach ($games as $slug => $gameData) {
            Game::updateOrCreate(
                ['slug' => $slug],
                [
                    'slug' => $slug,
                    'title' => $gameData['title'],
                    'type' => $gameData['type'],
                    'status' => 'published',
                    'short_description' => $gameData['short_description'],
                    'rules_md' => $gameData['rules_md'],
                    'options_json' => $gameData['options_json'] ?? null,
                ]
            );
        }

        $this->command->info('Seeded '.count($games).' games.');
    }
}
