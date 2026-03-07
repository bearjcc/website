<?php

declare(strict_types=1);

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

        $catalog = config('games.catalog', []);
        $ticTacToe = null;

        foreach ($catalog as $slug => $gameData) {
            $game = Game::create([
                'slug' => $slug,
                'title' => $gameData['title'],
                'type' => $gameData['type'],
                'status' => 'published',
                'short_description' => $gameData['short_description'],
                'rules_md' => $gameData['rules_md'],
                'options_json' => $gameData['options_json'] ?? null,
            ]);

            if ($slug === 'tic-tac-toe') {
                $ticTacToe = $game;
            }
        }

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
        if ($ticTacToe !== null) {
            FeatureBlock::create([
                'kind' => 'game',
                'ref_id' => $ticTacToe->id,
                'order' => 1,
            ]);
        }
    }
}
