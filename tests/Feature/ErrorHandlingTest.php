<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_returns_ok_and_timestamp(): void
    {
        $response = $this->get(route('health'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(['status', 'timestamp']);
        $response->assertJsonPath('status', 'ok');
    }

    public function test_returns_404_for_invalid_game_slug(): void
    {
        $response = $this->get(route('games.play', 'non-existent-game'));

        $response->assertStatus(404);
    }

    public function test_handles_empty_game_list_gracefully(): void
    {
        // No games in database
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(__('ui.tagline'));
    }

    public function test_handles_missing_game_attributes(): void
    {
        $game = Game::factory()->create([
            'slug' => 'test-game',
            'title' => 'Test Game',
            'short_description' => null,
            'rules_md' => null,
            'status' => 'published',
        ]);

        $response = $this->get(route('games.play', $game));

        $response->assertStatus(200);
        $response->assertSee('Game Not Available');
    }

    public function test_all_navigation_links_work(): void
    {
        $routes = [
            'home' => '/',
            'games.index' => '/games',
            'about' => '/about',
        ];

        foreach ($routes as $name => $path) {
            $response = $this->get($path);
            $response->assertStatus(200);
        }
    }

    public function test_handles_xhr_requests(): void
    {
        $response = $this->get(route('home'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
    }
}
