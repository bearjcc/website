<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LetterWalkerScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_score_and_get_successful_response(): void
    {
        $payload = [
            'score' => 120,
            'moves' => 15,
            'words_found' => 1,
            'puzzle_number' => 1,
            'player_name' => 'Test Player',
        ];

        $response = $this->postJson('/api/letter-walker/score', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('letter_walker_scores', [
            'player_name' => 'Test Player',
            'score' => 120,
            'moves' => 15,
            'words_found' => 1,
            'puzzle_number' => 1,
        ]);
    }

    public function test_daily_endpoint_returns_only_today_scores(): void
    {
        $response = $this->getJson('/api/letter-walker/scores/daily');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertArrayHasKey('scores', $response->json());
        $this->assertArrayHasKey('date', $response->json());
    }

    public function test_letter_walker_page_loads_and_has_leaderboard_container(): void
    {
        Game::factory()->create(['slug' => 'letter-walker', 'status' => 'published']);
        $response = $this->get('/letter-walker');

        $response->assertStatus(200);
        $response->assertSee('Today&#039;s Top Scores', false);
        $response->assertSee('id="leaderboard-body"', false);
    }
}
