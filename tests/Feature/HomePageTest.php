<?php

namespace Tests\Feature;

use App\Models\Game;
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

        // Footer copyright
        $response->assertSee('Ursa Minor Games');
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

    public function test_homepage_shows_all_published_games_in_carousel(): void
    {
        // Create multiple games
        Game::factory()->count(5)->published()->create();

        $response = $this->get('/');

        $html = $response->getContent();

        // Carousel shows all games (just makes browsing simpler for user)
        // Verify carousel structure exists
        $this->assertStringContainsString('um-carousel', $html);

        // Verify games are present
        preg_match_all('/href="[^"]*\/games\/([^"]+)"/i', $html, $matches);
        $uniqueGames = array_unique($matches[1]);

        $this->assertEquals(5, count($uniqueGames), 'Homepage carousel should show all published games');
    }

    public function test_homepage_handles_empty_game_state(): void
    {
        // Ensure no games exist
        Game::query()->delete();

        $response = $this->get('/');

        $response->assertStatus(200);

        // Carousel should exist but be empty (graceful empty state)
        // No crash or error
        $this->assertTrue(true);
    }

    // Blog section removed - minimal homepage philosophy

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

        // Should have Vite references (dev mode has localhost:5173, prod has /build/)
        $this->assertTrue(
            str_contains($html, '@vite') || str_contains($html, '/build/') || str_contains($html, '5173'),
            'Homepage should load assets via Vite'
        );
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
