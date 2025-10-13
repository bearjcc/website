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

        // Footer should have the horizon gradient line
        $this->assertStringContainsString('bg-gradient-to-r', $html);
        $this->assertStringContainsString('from-white/0', $html);
    }

    public function test_footer_uses_new_copy(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Should use the new footer copy
        $response->assertSee('The sky is the limit.');

        // Should NOT contain old Southern Cross reference
        $response->assertDontSee('Southern Cross');
    }
}
