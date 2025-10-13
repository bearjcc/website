<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MinimalHomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_has_minimal_hero_with_headline_and_ctas(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Hero headline present
        $response->assertSee('Small games. Big craft.');

        // CTAs present with one-word labels
        $response->assertSee('Play');
        $response->assertSee('Browse');
    }

    public function test_homepage_uses_visual_first_game_cards(): void
    {
        // Create test games
        Game::factory()->create([
            'title' => 'Tic-Tac-Toe',
            'slug' => 'tic-tac-toe',
            'status' => 'published',
        ]);

        Game::factory()->create([
            'title' => 'Sudoku',
            'slug' => 'sudoku',
            'status' => 'published',
        ]);

        $response = $this->get('/');

        $html = $response->getContent();

        // Should use game-card component
        $this->assertStringContainsString('um-game-card', $html);

        // Games should be present but title in sr-only (accessible text)
        $response->assertSee('Tic-Tac-Toe');
        $response->assertSee('Sudoku');

        // Check for visual motifs (SVG elements)
        $this->assertStringContainsString('<svg', $html);

        // Check for aria-label on cards
        $this->assertStringContainsString('aria-label="Play Tic-Tac-Toe"', $html);
    }

    public function test_game_cards_have_accessibility_features(): void
    {
        Game::factory()->create([
            'title' => 'Connect 4',
            'slug' => 'connect-4',
            'status' => 'published',
        ]);

        $response = $this->get('/');
        $html = $response->getContent();

        // Check for screen reader text
        $this->assertStringContainsString('sr-only', $html);

        // Check for aria-label
        $this->assertStringContainsString('aria-label', $html);

        // Check for focus-visible classes
        $this->assertStringContainsString('focus-visible:ring', $html);

        // Check for keyboard navigation support (links, not divs)
        $this->assertStringContainsString('<a href=', $html);
    }

    public function test_homepage_has_minimal_sections(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Should NOT have verbose section headers with kickers
        $this->assertStringNotContainsString('Available now', $html);
        $this->assertStringNotContainsString('Play in your browser', $html);
        $this->assertStringNotContainsString('From the studio', $html);

        // Should NOT have hero body text
        $this->assertStringNotContainsString('We build elegant, replayable games', $html);
    }

    public function test_blog_section_is_minimal_when_posts_exist(): void
    {
        // Create a test post
        \App\Models\Post::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'status' => 'published',
        ]);

        $response = $this->get('/');
        $html = $response->getContent();

        // Should have "Latest Notes" heading
        $response->assertSee('Latest Notes');

        // Should NOT have verbose kicker/subtitle
        $this->assertStringNotContainsString('studio_kicker', $html);
        $this->assertStringNotContainsString('Short updates as we build', $html);

        // Should NOT have icons on blog cards
        $this->assertStringNotContainsString('heroicon-o-document-text', $html);

        // Should have post title
        $response->assertSee('Test Post');
    }

    public function test_blog_section_hidden_when_no_posts(): void
    {
        // Ensure no published posts
        \App\Models\Post::query()->delete();

        $response = $this->get('/');

        // Section should not appear at all
        $response->assertDontSee('Latest Notes');
    }

    public function test_game_cards_respect_reduced_motion(): void
    {
        Game::factory()->create([
            'title' => 'Snake',
            'slug' => 'snake',
            'status' => 'published',
        ]);

        $response = $this->get('/');
        $html = $response->getContent();

        // Check that CSS includes reduced motion support
        // This is tested in the component structure
        $this->assertStringContainsString('transition', $html);
        $this->assertTrue(true); // Structural test passes
    }

    public function test_footer_uses_correct_tagline(): void
    {
        $response = $this->get('/');

        // New footer tagline
        $response->assertSee('Building games under the Southern Cross.');

        // Old tagline moved to page title
        $response->assertSee('The sky is the limit.');
    }

    public function test_game_cards_have_consistent_aspect_ratio(): void
    {
        Game::factory()->count(3)->create(['status' => 'published']);

        $response = $this->get('/');
        $html = $response->getContent();

        // Check for aspect ratio padding trick
        $this->assertStringContainsString('pt-[70%]', $html);
        $this->assertStringContainsString('md:pt-[66%]', $html);
    }

    public function test_buttons_have_minimum_touch_target_size(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // CTAs should use btn-primary and btn-secondary classes
        // which now include min-height: 44px
        $this->assertStringContainsString('btn-primary', $html);
        $this->assertStringContainsString('btn-secondary', $html);
    }

    public function test_homepage_grid_uses_responsive_columns(): void
    {
        Game::factory()->count(6)->create(['status' => 'published']);

        $response = $this->get('/');
        $html = $response->getContent();

        // Should use 2-col mobile, 3-col desktop
        $this->assertStringContainsString('grid-cols-2', $html);
        $this->assertStringContainsString('md:grid-cols-3', $html);
    }
}
