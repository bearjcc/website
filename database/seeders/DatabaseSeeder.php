<?php

namespace Database\Seeders;

use App\Models\FeatureBlock;
use App\Models\Game;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ursaminor.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        $contributor = User::create([
            'name' => 'Contributor User',
            'email' => 'contributor@ursaminor.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_CONTRIBUTOR,
        ]);

        User::create([
            'name' => 'Guest User',
            'email' => 'guest@ursaminor.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_GUEST,
        ]);

        // Create sample games
        $ticTacToe = Game::create([
            'slug' => 'tic-tac-toe',
            'title' => 'Tic-Tac-Toe',
            'type' => 'strategy',
            'status' => 'published',
            'short_description' => 'Classic 3x3 grid game. Get three in a row to win!',
            'rules_md' => "## Rules\n\n- Players take turns placing X or O on a 3x3 grid\n- First player to get 3 in a row (horizontal, vertical, or diagonal) wins\n- If all 9 squares are filled with no winner, it's a draw",
            'options_json' => ['difficulty' => 'easy', 'ai_enabled' => true],
        ]);

        Game::create([
            'slug' => 'sudoku',
            'title' => 'Sudoku',
            'type' => 'puzzle',
            'status' => 'draft',
            'short_description' => 'Fill the 9x9 grid with digits so each column, row, and 3x3 section contains 1-9.',
            'rules_md' => "## Rules\n\n- Fill the grid so that every row, column, and 3×3 box contains the numbers 1 through 9\n- Each number can only appear once per row, column, and box\n- Use logic and deduction to solve the puzzle",
            'options_json' => ['difficulty' => 'medium', 'hints_enabled' => true],
        ]);

        Game::create([
            'slug' => 'minesweeper',
            'title' => 'Minesweeper',
            'type' => 'puzzle',
            'status' => 'draft',
            'short_description' => 'Clear the board without detonating any mines. Use clues to deduce safe squares.',
            'rules_md' => "## Rules\n\n- Click squares to reveal them\n- Numbers indicate how many mines are adjacent\n- Right-click to flag suspected mines\n- Clear all non-mine squares to win",
            'options_json' => ['grid_size' => '10x10', 'mine_count' => 15],
        ]);

        // Create sample blog posts
        Post::create([
            'slug' => 'welcome-to-ursa-minor',
            'title' => 'Welcome to Ursa Minor',
            'body_md' => "# Welcome!\n\nWe're excited to launch Ursa Minor, your new destination for browser-based games and creative projects.\n\n## What to Expect\n\nWe'll be regularly updating our game collection and sharing insights about game development, design decisions, and more.\n\nStay tuned for new content!",
            'status' => 'published',
        ]);

        Post::create([
            'slug' => 'browser-games-renaissance',
            'title' => 'The Browser Games Renaissance',
            'body_md' => "# Browser Games Are Back\n\nWith modern web technologies like HTML5 Canvas, WebGL, and powerful JavaScript frameworks, browser games have never been better.\n\n## Why Browser Games?\n\n- **Instant Access**: No downloads or installations\n- **Cross-Platform**: Works on any device with a browser\n- **Always Updated**: Latest version automatically\n\nWe're excited to be part of this renaissance!",
            'status' => 'published',
        ]);

        // Create feature block for the published game
        FeatureBlock::create([
            'kind' => 'game',
            'ref_id' => $ticTacToe->id,
            'order' => 1,
        ]);
    }
}
