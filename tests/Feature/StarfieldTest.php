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

        // Footer should have the sunset horizon gradient line
        $this->assertStringContainsString('bg-gradient-to-r', $html);
        $this->assertStringContainsString('from-[rgba(255,170,120,0)]', $html);

        // Should have the mountain silhouette SVG
        $this->assertStringContainsString('viewBox="0 0 1200 40"', $html);
    }

    public function test_footer_uses_new_copy(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Should use the new footer copy
        $response->assertSee('The sky is the limit.');

        // Should have copyright with all rights reserved
        $response->assertSee('All rights reserved');

        // Should NOT contain old Southern Cross reference
        $response->assertDontSee('Southern Cross');
    }
}
