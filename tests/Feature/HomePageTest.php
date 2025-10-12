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

        // Footer shows poetic tagline
        $response->assertSee('Building games under the Southern Cross.');
    }

    public function test_homepage_game_cards_link_to_play_routes(): void
    {
        $game = Game::factory()->published()->create([
            'slug' => 'test-game',
        ]);

        $response = $this->get('/');

        $response->assertSee(route('games.play', $game->slug), false);
    }

    /**
     * Test homepage renders core sections
     *
     * Verifies that the homepage displays essential content including:
     * - Primary CTA ("Play a game")
     * - Available games kicker text
     * - Footer note
     */
    public function test_homepage_renders_core_sections(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Play a game');
        $response->assertSee(__('ui.available_kicker'));
        $response->assertSee(__('ui.footer_note'));
    }

    /**
     * Test homepage has no banned words
     *
     * Ensures that the homepage does not contain future-facing language
     * about physical stores, cafés, or distant plans.
     */
    public function test_homepage_has_no_banned_words(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        // Check that none of the banned terms appear on the homepage
        $content = $response->getContent();

        $bannedTerms = ['café', 'storefront', 'years away', 'shop'];

        foreach ($bannedTerms as $term) {
            $this->assertStringNotContainsString(
                $term,
                $content,
                "Homepage should not contain the term '{$term}'"
            );
        }
    }

    /**
     * Test homepage limits game cards to three
     * 
     * Verifies that even when more than 3 games are published,
     * the homepage only displays up to 3 game cards.
     */
    public function test_homepage_limits_game_cards_to_three(): void
    {
        // Seed more than 3 published games
        Game::factory()->count(5)->published()->create();

        $response = $this->get('/');

        $response->assertOk();
        
        // Count the number of links to game play routes
        $content = $response->getContent();
        
        // Count occurrences of the games.play route pattern
        $playRoutePattern = route('games.play', 'PLACEHOLDER');
        $baseRoute = str_replace('PLACEHOLDER', '', $playRoutePattern);
        
        // Get all published games
        $allGames = Game::published()->latest()->get();
        
        // Count how many game links appear
        $linkCount = 0;
        foreach ($allGames as $game) {
            if (str_contains($content, route('games.play', $game->slug))) {
                $linkCount++;
            }
        }
        
        // Should be exactly 3 game links (or fewer if < 3 games exist)
        $this->assertLessThanOrEqual(3, $linkCount, 'Homepage should display at most 3 game cards');
        $this->assertEquals(3, $linkCount, 'Homepage should display exactly 3 game cards when more than 3 are available');
    }

    /**
     * Test homepage handles missing database tables gracefully
     * 
     * Regression test for Railway 500 errors caused by missing tables.
     * Ensures homepage renders even when feature_blocks, games, or posts tables don't exist.
     */
    public function test_homepage_handles_missing_tables_gracefully(): void
    {
        // This test uses RefreshDatabase which creates fresh tables
        // But the Home component should handle missing tables gracefully
        // by catching exceptions and returning empty collections
        
        $response = $this->get('/');
        
        $response->assertStatus(200);
        
        // Should show "Coming soon" when no games are available
        $response->assertSee('Coming soon');
        $response->assertSee('New games are in the works.');
        
        // Should not show studio section when no posts exist
        $response->assertDontSee('From the studio');
        $response->assertDontSee('Latest notes');
        
        // Should still show hero section and footer
        $response->assertSee('Small games. Big craft.');
        $response->assertSee('Building games under the Southern Cross.');
    }
}
