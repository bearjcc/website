<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Comprehensive test suite to ensure all pages provide
 * a cohesive, joyful, simple, elegant experience.
 */
class SiteCohesionTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_public_routes_are_accessible(): void
    {
        $routes = [
            'Home' => '/',
            'Games Index' => '/games',
            'About' => '/about',
        ];

        foreach ($routes as $name => $path) {
            $response = $this->get($path);
            $response->assertStatus(200, "{$name} page should be accessible");
        }
    }

    public function test_all_navigation_links_are_present_and_functional(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $html = $response->getContent();
        $this->assertStringContainsString('href="/"', $html);
        $this->assertStringContainsString('href="/games"', $html);
        $this->assertStringContainsString('href="/about"', $html);
    }

    public function test_all_published_games_are_accessible(): void
    {
        $games = Game::factory()->count(6)->create(['status' => 'published']);

        foreach ($games as $game) {
            $response = $this->get(route('games.play', $game->slug));
            $response->assertStatus(200);
        }
    }

    public function test_all_pages_load_without_errors(): void
    {
        $pages = ['/', '/games', '/about'];

        foreach ($pages as $page) {
            $response = $this->get($page);

            $response->assertStatus(200);

            // Should have proper structure
            $html = $response->getContent();
            $this->assertStringContainsString('<!DOCTYPE html>', $html);
            $this->assertStringContainsString('</html>', $html);
        }
    }

    public function test_all_pages_have_back_to_top(): void
    {
        $pages = ['/', '/games', '/about'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee(__('ui.back_to_top'), false);
        }
    }

    public function test_all_interactive_elements_have_focus_styles(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Check for focus-visible classes (our standard)
        $this->assertStringContainsString('focus-visible:', $html, 'Interactive elements should have focus styles');
    }

    public function test_all_pages_respect_reduced_motion(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Check for reduced motion considerations in styles or scripts
        $this->assertStringContainsString('prefers-reduced-motion', $html);
    }

    public function test_all_pages_have_proper_semantic_structure(): void
    {
        $pages = ['/', '/games', '/about'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $html = $response->getContent();

            // Check for semantic HTML
            $this->assertStringContainsString('<header', $html);
            $this->assertStringContainsString('<main', $html);
            $this->assertStringContainsString('<footer', $html);
        }
    }

    public function test_no_emojis_in_rendered_output(): void
    {
        Game::factory()->create(['status' => 'published', 'slug' => 'tic-tac-toe']);

        $pages = [
            '/',
            '/games',
            '/about',
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $html = $response->getContent();

            // Check for common emojis (not exhaustive, but covers most)
            $emojiPatterns = ['🎉', '🎮', '⭐', '🏆', '👍', '😀', '🤝', '💫', '✨', '💥'];

            foreach ($emojiPatterns as $emoji) {
                $this->assertStringNotContainsString($emoji, $html, 'No emojis allowed in output');
            }
        }
    }

    public function test_all_pages_use_calm_professional_copy(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Should NOT have excitable or salesy language
        $bannedPhrases = [
            'Amazing!',
            'Awesome!',
            'Incredible!',
            'Join us now!',
            'Sign up today!',
            '!!!',
        ];

        foreach ($bannedPhrases as $phrase) {
            $this->assertStringNotContainsString($phrase, $html, 'Copy should be calm and professional');
        }
    }

    public function test_all_buttons_meet_minimum_touch_target(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Nav and buttons use min-h-[44px] or btn classes for 44px minimum touch target
        $this->assertStringContainsString('min-h-[44px]', $html, 'Interactive elements should meet 44px minimum touch target');
    }

    public function test_all_pages_load_within_reasonable_time(): void
    {
        $start = microtime(true);

        $response = $this->get('/');

        $end = microtime(true);
        $duration = ($end - $start) * 1000; // ms

        $response->assertStatus(200);

        // Page should load reasonably fast (< 1000ms in tests)
        $this->assertLessThan(1000, $duration, 'Page should load quickly');
    }
}
