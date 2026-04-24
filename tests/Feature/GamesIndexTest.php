<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Small games live on the dedicated games host (path on ursaminor.games, e.g. /sudoku).
 * This marketing app only issues 301s to that host; it does not render a local games index.
 */
class GamesIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_games_index_permanent_redirects_to_apex_index(): void
    {
        $this->assertRedirectsToSmallGamesApex($this->get(route('games.index')), '');
    }

    public function test_published_game_show_and_play_routes_redirect_to_apex(): void
    {
        $game = Game::factory()->create([
            'slug' => 'sudoku',
            'status' => 'published',
        ]);

        $this->assertRedirectsToSmallGamesApex($this->get(route('games.show', $game->slug)), $game->slug);
        $this->assertRedirectsToSmallGamesApex($this->get(route('games.play', $game->slug)), $game->slug);
    }
}
