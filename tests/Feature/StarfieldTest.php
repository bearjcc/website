<?php

namespace Tests\Feature;

use Tests\TestCase;

class StarfieldTest extends TestCase
{
    public function test_homepage_includes_starfield_script(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Starfield should be loaded via app.js bundle
        $html = $response->getContent();
        $this->assertStringContainsString('app.js', $html);
    }

    public function test_homepage_has_app_wrapper_for_layering(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Should have the um-app wrapper for z-index layering
        $this->assertStringContainsString('id="um-app"', $html);
    }

    public function test_footer_has_horizon_line(): void
    {
        $response = $this->get('/');

        $html = $response->getContent();

        // Footer should have the horizon elements (styled in CSS, not inline)
        $this->assertStringContainsString('um-horizon-line', $html);
        $this->assertStringContainsString('um-horizon-silhouette', $html);
        $this->assertStringContainsString('um-horizon-footer', $html);
    }

    public function test_footer_is_minimal(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Hero should use the tagline
        $response->assertSee('The sky is the limit.');

        // Footer is minimal - just copyright
        $response->assertSee('Ursa Minor Games');

        // Should NOT contain "All rights reserved" (removed for minimalism)
        // Should NOT contain old Southern Cross reference
        $response->assertDontSee('Southern Cross');
    }
}
