<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_page_routes_redirect_to_apex(): void
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
            $this->assertRedirectsToSmallGamesApex($this->get('/'.$slug), $slug);
        }
    }

    public function test_play_routes_redirect_to_apex(): void
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
            $this->assertRedirectsToSmallGamesApex($this->get('/'.$slug.'/play'), $slug);
        }
    }

    public function test_legacy_games_paths_permanently_redirect_to_apex(): void
    {
        Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'status' => 'published',
        ]);

        $fromPaths = [
            '/games/tic-tac-toe', '/games/connect-4', '/games/sudoku', '/games/twenty-forty-eight',
            '/games/minesweeper', '/games/snake', '/games/checkers', '/games/chess', '/games/letter-walker',
        ];

        foreach ($fromPaths as $from) {
            $slug = basename($from);
            $this->assertRedirectsToSmallGamesApex($this->get($from), $slug);
        }
    }

    public function test_dynamic_legacy_games_slug_redirects_to_apex(): void
    {
        $game = Game::factory()->create([
            'slug' => 'custom-game',
            'status' => 'published',
        ]);

        $this->assertRedirectsToSmallGamesApex($this->get('/games/'.$game->slug), $game->slug);
    }

    public function test_legacy_games_play_paths_redirect_to_apex(): void
    {
        Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'status' => 'published',
        ]);

        $this->assertRedirectsToSmallGamesApex($this->get('/games/tic-tac-toe/play'), 'tic-tac-toe');
    }

    public function test_marketing_home_lists_game_titles_games_index_redirects_game_pages_redirect_to_apex(): void
    {
        Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'title' => 'Tic-Tac-Toe',
            'status' => 'published',
        ]);

        $this->get(route('home'))->assertStatus(200)->assertSee('Tic-Tac-Toe');
        $this->assertRedirectsToSmallGamesApex($this->get(route('games.index')), '');

        $this->assertRedirectsToSmallGamesApex($this->get('/tic-tac-toe'), 'tic-tac-toe');
        $this->assertRedirectsToSmallGamesApex($this->get('/tic-tac-toe/play'), 'tic-tac-toe');

        $response404 = $this->get('/unknown-slug');
        $response404->assertStatus(404);
        $response404->assertSee('Game not found');
        $response404->assertSee('Browse games');
    }
}
