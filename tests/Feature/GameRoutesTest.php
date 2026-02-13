<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_top_level_game_routes_are_accessible(): void
    {
        // Seed canonical games needed by Livewire components
        $slugs = [
            'tic-tac-toe',
            'connect-4',
            'sudoku',
            'minesweeper',
            'snake',
            'checkers',
            'chess',
            'letter-walker',
            'twenty-forty-eight',
        ];

        foreach ($slugs as $slug) {
            Game::factory()->create([
                'slug' => $slug,
                'status' => 'published',
            ]);
        }

        $staticGamePaths = [
            '/tic-tac-toe',
            '/connect-4',
            '/sudoku',
            '/twenty-forty-eight',
            '/minesweeper',
            '/snake',
            '/checkers',
            '/letter-walker',
        ];

        foreach ($staticGamePaths as $path) {
            $response = $this->get($path);

            $response->assertStatus(200, "{$path} should be accessible at top level");
        }
    }

    public function test_legacy_games_paths_permanently_redirect_to_top_level(): void
    {
        Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'status' => 'published',
        ]);

        $redirects = [
            '/games/tic-tac-toe' => '/tic-tac-toe',
            '/games/connect-4' => '/connect-4',
            '/games/sudoku' => '/sudoku',
            '/games/twenty-forty-eight' => '/twenty-forty-eight',
            '/games/minesweeper' => '/minesweeper',
            '/games/snake' => '/snake',
            '/games/checkers' => '/checkers',
            '/games/chess' => '/chess',
            '/games/letter-walker' => '/letter-walker',
        ];

        foreach ($redirects as $from => $to) {
            $response = $this->get($from);

            $response->assertRedirect($to);
            $this->assertSame(301, $response->getStatusCode(), "{$from} should permanently redirect to {$to}");
        }
    }

    public function test_dynamic_legacy_games_slug_redirects_to_top_level_slug(): void
    {
        $game = Game::factory()->create([
            'slug' => 'custom-game',
            'status' => 'published',
        ]);

        $response = $this->get('/games/'.$game->slug);

        $response->assertRedirect('/'.$game->slug);
        $this->assertSame(301, $response->getStatusCode());
    }
}
