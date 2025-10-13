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
        $games = [
            [
                'slug' => 'tic-tac-toe',
                'title' => 'Tic-Tac-Toe',
                'type' => 'board',
                'status' => 'published',
                'short_description' => 'Three in a row.',
                'rules_md' => 'Get three in a row to win.',
                'options_json' => json_encode(['difficulty' => 'medium', 'ai_enabled' => true]),
            ],
            [
                'slug' => 'sudoku',
                'title' => 'Sudoku',
                'type' => 'puzzle',
                'status' => 'published',
                'short_description' => 'Fill the grid.',
                'rules_md' => 'Each row, column, and 3x3 box contains digits 1-9.',
                'options_json' => json_encode(['difficulty' => 'easy']),
            ],
            [
                'slug' => 'minesweeper',
                'title' => 'Minesweeper',
                'type' => 'puzzle',
                'status' => 'published',
                'short_description' => 'Clear the board.',
                'rules_md' => 'Reveal squares without hitting mines.',
                'options_json' => json_encode(['grid_size' => '8x8', 'mine_count' => 10]),
            ],
            [
                'slug' => 'connect-4',
                'title' => 'Connect 4',
                'type' => 'board',
                'status' => 'published',
                'short_description' => 'Four in a row.',
                'rules_md' => 'Connect four vertically, horizontally, or diagonally.',
                'options_json' => json_encode(['ai_enabled' => true]),
            ],
            [
                'slug' => 'snake',
                'title' => 'Snake',
                'type' => 'puzzle',
                'status' => 'published',
                'short_description' => 'Grow the snake.',
                'rules_md' => 'Eat food. Avoid walls and your tail.',
                'options_json' => json_encode(['speed' => 'normal', 'grid_size' => 20]),
            ],
            [
                'slug' => 'twenty-forty-eight',
                'title' => '2048',
                'type' => 'puzzle',
                'status' => 'published',
                'short_description' => 'Combine tiles.',
                'rules_md' => 'Merge matching numbers to reach 2048.',
                'options_json' => json_encode(['grid_size' => 4]),
            ],
        ];

        foreach ($games as $gameData) {
            // Idempotent: update or create
            Game::updateOrCreate(
                ['slug' => $gameData['slug']],
                $gameData
            );
        }

        $this->command->info('Seeded '.count($games).' games.');
    }
}
