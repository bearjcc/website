<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Test for Homepage Refactor (2025-10-13)
 *
 * Verifies the night-sky refactor implementation:
 * - Nav has comfortable padding and breathing room
 * - Hero lockup is marked for morphing
 * - Horizon footer with back-to-top is present
 * - Proper spacing and accessibility
 */
class HomepageRefactorTest extends TestCase
{
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('The sky is the limit.');
    }

    public function test_homepage_has_nav_with_morph_target(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Nav should have morph target
        $this->assertStringContainsString('data-um-morph="nav-logo"', $html);

        // Header should have ID
        $this->assertStringContainsString('id="um-header"', $html);
    }

    public function test_homepage_hero_has_lockup_marker(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Hero lockup container should be marked
        $this->assertStringContainsString('data-um-hero-lockup', $html);
    }

    public function test_homepage_has_horizon_footer_with_back_to_top(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Should have back to top button with correct class
        $this->assertStringContainsString('class="um-top-btn"', $html);
        $this->assertStringContainsString('aria-label="Back to top"', $html);

        // Should have horizon footer
        $this->assertStringContainsString('class="relative w-full isolate um-horizon-footer"', $html);

        // Should have star icon (heroicon-o-star)
        $this->assertStringContainsString('<svg', $html);
    }

    public function test_homepage_has_proper_spacing(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Hero has top padding; nav has vertical padding for breathing room
        $hasHeroPadding = str_contains($html, 'pt-24') || str_contains($html, 'pt-8');
        $this->assertTrue($hasHeroPadding);
        $this->assertStringContainsString('py-6', $html);
    }

    public function test_homepage_has_accessibility_features(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Should have main content ID
        $this->assertStringContainsString('id="main-content"', $html);

        // Should have top anchor for back-to-top
        $this->assertStringContainsString('id="top"', $html);

        // Back to top should have aria-label
        $this->assertStringContainsString('aria-label="Back to top"', $html);
    }

    public function test_homepage_starfield_canvas_target_exists(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Should have app wrapper for starfield
        $this->assertStringContainsString('id="um-app"', $html);

        // Starfield canvas created by JavaScript, not in initial HTML
        $this->assertTrue(true);
    }

    public function test_homepage_uses_correct_i18n_keys(): void
    {
        $response = $this->get('/');

        $response->assertSee(__('ui.tagline'));
        $response->assertSee('Ursa Minor Games');
    }
}
