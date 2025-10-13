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
        $response->assertSee('Small games. Big craft.');
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

        // Should have back to top link
        $this->assertStringContainsString('href="#top"', $html);
        $this->assertStringContainsString('aria-label="Back to top"', $html);

        // Should have sunset gradient line (via class)
        $this->assertStringContainsString('bg-gradient-to-r', $html);

        // Should have silhouette SVG
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('viewBox="0 0 1200 40"', $html);
    }

    public function test_homepage_has_proper_spacing(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Hero should have increased top padding (pt-24 = 96px or pt-32 = 128px on md)
        $this->assertStringContainsString('pt-24', $html);
        $this->assertStringContainsString('md:pt-32', $html);

        // Nav should have py-4 (32px vertical padding)
        $this->assertStringContainsString('py-4', $html);
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
        $this->assertStringContainsString('class="relative z-10"', $html);
    }

    public function test_homepage_uses_correct_i18n_keys(): void
    {
        $response = $this->get('/');

        // Should use the new hero headline
        $response->assertSee(__('ui.hero_headline'));
        $response->assertSee(__('ui.hero_body'));
        $response->assertSee(__('ui.cta_play'));
        $response->assertSee(__('ui.footer_note_primary'));
    }
}
