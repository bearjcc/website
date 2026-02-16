<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamesIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_games_index_renders_successfully(): void
    {
        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertSee(__('ui.games_title'));
    }

    public function test_games_index_shows_published_games(): void
    {
        // Create published and draft games
        $publishedGames = Game::factory()->count(3)->create(['status' => 'published']);
        Game::factory()->count(2)->create(['status' => 'draft']);

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);

        // Should see published games
        foreach ($publishedGames as $game) {
            $response->assertSee($game->title);
        }
    }

    public function test_games_index_does_not_show_draft_games(): void
    {
        $draftGame = Game::factory()->create([
            'title' => 'Draft Game Test',
            'status' => 'draft',
        ]);

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Draft Game Test');
    }

    public function test_games_index_shows_empty_state_when_no_games(): void
    {
        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertSee(__('ui.games_empty'));
    }

    public function test_games_index_game_cards_are_links(): void
    {
        $game = Game::factory()->create([
            'slug' => 'test-game',
            'status' => 'published',
        ]);

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertSee(route('games.show', $game->slug), false);
    }

    public function test_games_index_has_proper_aria_labels(): void
    {
        $game = Game::factory()->create([
            'title' => 'Test Game',
            'status' => 'published',
        ]);

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertSee('Play Test Game', false); // aria-label
    }

    public function test_games_index_uses_correct_motifs(): void
    {
        $tictactoe = Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'status' => 'published',
        ]);

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        // The view should contain SVG motif markup
        $this->assertStringContainsString('<svg', $response->getContent());
    }
}
