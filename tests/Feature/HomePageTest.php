<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_homepage_has_core_sections(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Hero section content
        $response->assertSee(__('ui.tagline'));
        $response->assertSee(__('ui.cta_play'));

        // Available Now section
        $response->assertSee(__('ui.available_kicker'));
        $response->assertSee(__('ui.available_title'));

        // Footer
        $response->assertSee(__('ui.footer_note_primary'));
    }

    public function test_homepage_has_no_banned_words(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Check for banned terms (future-facing promises)
        $bannedTerms = [
            'café opening',
            'storefront opening',
            'shop opening',
            'years away',
            'coming in 2026',
        ];

        foreach ($bannedTerms as $term) {
            $this->assertStringNotContainsString(
                $term,
                $html,
                "Homepage should not mention '{$term}' (future-facing promise)"
            );
        }
    }

    public function test_homepage_limits_game_cards_to_three(): void
    {
        // Create more than 3 games
        Game::factory()->count(5)->published()->create();

        $response = $this->get('/');

        $html = $response->getContent();

        // Count unique game cards by extracting unique slugs
        // Each game card has a unique href to games.play route
        preg_match_all('/href="[^"]*\/games\/([^"]+)"/i', $html, $matches);
        $uniqueGames = array_unique($matches[1]);
        $gameCardCount = count($uniqueGames);

        $this->assertLessThanOrEqual(
            3,
            $gameCardCount,
            'Homepage should display maximum 3 unique game cards'
        );
    }

    public function test_homepage_shows_coming_soon_cards_when_no_games(): void
    {
        // Ensure no games exist
        Game::query()->delete();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(__('ui.coming_soon_title'));
    }

    public function test_homepage_hides_studio_section_when_no_posts(): void
    {
        // Ensure no posts exist
        Post::query()->delete();

        $response = $this->get('/');

        $response->assertStatus(200);

        // Studio section should not appear
        $response->assertDontSee(__('ui.studio_kicker'));
    }

    public function test_homepage_shows_studio_section_when_posts_exist(): void
    {
        // Create a published post
        Post::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        // Studio section should appear
        $response->assertSee(__('ui.studio_kicker'));
        $response->assertSee(__('ui.studio_title'));
    }

    public function test_homepage_has_proper_html_structure(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Check for key structural elements
        $this->assertStringContainsString('<header', $html);
        $this->assertStringContainsString('<main', $html);
        $this->assertStringContainsString('<footer', $html);

        // Check sections are well-formed
        $this->assertStringContainsString('id="main-content"', $html);
    }

    public function test_homepage_uses_vite_assets(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Should use Vite, not old public/style.css
        $this->assertStringNotContainsString(
            'asset(\'style.css\')',
            $html,
            'Homepage should use Vite assets, not legacy public/style.css'
        );

        // Should have Vite manifest references
        $this->assertStringContainsString('build', $html);
    }

    public function test_primary_cta_uses_accent_color(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Primary CTA should use btn-primary class (which uses accent color)
        $this->assertStringContainsString('btn-primary', $html);
    }

    public function test_homepage_has_conversion_tracking_attribute(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Primary CTA should have data-um-goal attribute for tracking
        $this->assertStringContainsString('data-um-goal="hero_play_click"', $html);
    }
}
