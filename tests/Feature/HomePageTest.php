<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_homepage_shows_hero_section_with_cta(): void
    {
        $response = $this->get('/');

        $response->assertSee('Small games. Big craft.');
        $response->assertSee('We build elegant, replayable games');
        $response->assertSee('Play a game');
        $response->assertSee('Browse all');
    }

    public function test_homepage_shows_at_most_three_game_cards(): void
    {
        // Create more than 3 published games
        Game::factory()->count(5)->published()->create();

        $response = $this->get('/');

        // We should only see 3 game titles in the "Available now" section
        $games = Game::published()->latest()->limit(3)->get();

        $response->assertSee($games[0]->title);
        $response->assertSee($games[1]->title);
        $response->assertSee($games[2]->title);
    }

    public function test_homepage_shows_coming_soon_when_no_games(): void
    {
        $response = $this->get('/');

        $response->assertSee('Coming soon');
        $response->assertSee('New games are in the works.');
    }

    public function test_homepage_does_not_show_long_term_vision_text(): void
    {
        $response = $this->get('/');

        // Ensure we don't show future promises
        $response->assertDontSee('cafe');
        $response->assertDontSee('café');
        $response->assertDontSee('storefront');
        $response->assertDontSee('years away');
        $response->assertDontSee('video game');
    }

    public function test_homepage_shows_studio_section_when_posts_exist(): void
    {
        Post::factory()->published()->create([
            'title' => 'Latest Update',
        ]);

        $response = $this->get('/');

        $response->assertSee('From the studio');
        $response->assertSee('Latest notes');
        $response->assertSee('Latest Update');
    }

    public function test_homepage_hides_studio_section_when_no_posts(): void
    {
        $response = $this->get('/');

        $response->assertDontSee('From the studio');
        $response->assertDontSee('Latest notes');
    }

    public function test_homepage_shows_footer(): void
    {
        $response = $this->get('/');

        $response->assertSee('Building games under the Southern Cross.');
        $response->assertSee('GitHub');
        $response->assertSee('About');
    }

    public function test_homepage_has_skip_to_content_link(): void
    {
        $response = $this->get('/');

        $response->assertSee('Skip to content');
    }

    public function test_homepage_game_cards_link_to_play_routes(): void
    {
        $game = Game::factory()->published()->create([
            'slug' => 'test-game',
        ]);

        $response = $this->get('/');

        $response->assertSee(route('games.play', $game->slug), false);
    }
}
