<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_page_routes_are_accessible(): void
    {
        $slugs = [
            'tic-tac-toe', 'connect-4', 'sudoku', 'twenty-forty-eight',
            'minesweeper', 'snake', 'checkers', 'chess', 'letter-walker',
        ];

        foreach ($slugs as $slug) {
            Game::factory()->create([
                'slug' => $slug,
                'status' => 'published',
            ]);
        }

        foreach ($slugs as $slug) {
            $response = $this->get("/{$slug}");
            $response->assertStatus(200, "/{$slug} (game page) should be accessible");
        }
    }

    public function test_play_routes_are_accessible(): void
    {
        $slugs = [
            'tic-tac-toe', 'connect-4', 'sudoku', 'twenty-forty-eight',
            'minesweeper', 'snake', 'checkers', 'chess', 'letter-walker',
        ];

        foreach ($slugs as $slug) {
            Game::factory()->create([
                'slug' => $slug,
                'status' => 'published',
            ]);
        }

        foreach ($slugs as $slug) {
            $response = $this->get("/{$slug}/play");
            if ($slug === 'letter-walker') {
                $response->assertRedirect('/letter-walker');
                $this->assertSame(301, $response->getStatusCode(), '/letter-walker/play should redirect to /letter-walker');
            } else {
                $response->assertStatus(200, "/{$slug}/play should be accessible");
            }
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

    public function test_legacy_games_play_paths_redirect_to_top_level_play(): void
    {
        Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'status' => 'published',
        ]);

        $response = $this->get('/games/tic-tac-toe/play');

        $response->assertRedirect('/tic-tac-toe/play');
        $this->assertSame(301, $response->getStatusCode());
    }

    /** Phase 7.1: Flow game page -> entry -> play; 404 for unknown slug. */
    public function test_game_flow_and_404(): void
    {
        Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'title' => 'Tic-Tac-Toe',
            'status' => 'published',
        ]);

        $this->get(route('home'))->assertStatus(200)->assertSee('Tic-Tac-Toe');
        $this->get(route('games.index'))->assertStatus(200)->assertSee('Tic-Tac-Toe');

        $gamePage = $this->get('/tic-tac-toe');
        $gamePage->assertStatus(200);
        $gamePage->assertSee('Tic-Tac-Toe');
        $gamePage->assertSee('Play');
        $gamePage->assertSee('tic-tac-toe/play');

        $entry = $this->get('/tic-tac-toe/play');
        $entry->assertStatus(200);
        $entry->assertSee('Start game');
        $entry->assertSee('Games');

        $response404 = $this->get('/unknown-slug');
        $response404->assertStatus(404);
        $response404->assertSee('Game not found');
        $response404->assertSee('Browse games');
    }
}
