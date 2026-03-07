<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use App\Games\TwentyFortyEight\TwentyFortyEightGame;
use App\Livewire\Games\TwentyFortyEight;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TwentyFortyEightTest extends TestCase
{
    use RefreshDatabase;

    protected Game $game;

    protected function setUp(): void
    {
        parent::setUp();

        Livewire::withoutLazyLoading();

        $this->game = Game::factory()->create([
            'slug' => 'twenty-forty-eight',
            'title' => '2048',
            'status' => 'published',
        ]);
    }

    public function test_component_renders_successfully(): void
    {
        Livewire::test(TwentyFortyEight::class)
            ->assertStatus(200)
            ->assertSee('2048')
            ->assertSee('direction pad');
    }

    public function test_game_metadata_uses_canonical_route_slug(): void
    {
        $game = new TwentyFortyEightGame();

        $this->assertSame('twenty-forty-eight', $game->slug());
        $this->assertSame('2048', $game->name());
    }

    public function test_move_updates_board_and_exposes_undo_state(): void
    {
        Livewire::test(TwentyFortyEight::class)
            ->set('board', [2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0])
            ->set('score', 0)
            ->set('isWon', false)
            ->set('isOver', false)
            ->call('move', 'left')
            ->assertSet('moveCount', 1)
            ->assertSet('score', 4)
            ->assertSet('canUndo', true);
    }

    public function test_undo_restores_previous_state(): void
    {
        Livewire::test(TwentyFortyEight::class)
            ->set('board', [2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0])
            ->set('score', 0)
            ->set('isWon', false)
            ->set('isOver', false)
            ->call('move', 'left')
            ->call('undo')
            ->assertSet('board', [2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0])
            ->assertSet('score', 0)
            ->assertSet('canUndo', false);
    }

    public function test_play_route_loads_successfully(): void
    {
        $response = $this->get(route('games.play', $this->game));

        $response->assertStatus(200);
        $response->assertSee('2048');
        $response->assertSee('Start game');
    }
}
