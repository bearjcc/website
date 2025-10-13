<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use App\Livewire\Games\TicTacToe;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicTacToeTest extends TestCase
{
    use RefreshDatabase;

    protected Game $game;

    protected function setUp(): void
    {
        parent::setUp();

        $this->game = Game::factory()->create([
            'slug' => 'tic-tac-toe',
            'title' => 'Tic-Tac-Toe',
            'status' => 'published',
        ]);
    }

    public function test_component_renders_successfully(): void
    {
        Livewire::test(TicTacToe::class)
            ->assertStatus(200)
            ->assertSee('Tic-Tac-Toe');
    }

    public function test_board_initializes_with_nine_empty_cells(): void
    {
        Livewire::test(TicTacToe::class)
            ->assertSet('board', array_fill(0, 9, null))
            ->assertSet('currentPlayer', 'X')
            ->assertSet('winner', null)
            ->assertSet('isDraw', false);
    }

    public function test_player_can_make_valid_move(): void
    {
        Livewire::test(TicTacToe::class)
            ->call('makeMove', 0)
            ->assertSet('board.0', 'X')
            ->assertSet('currentPlayer', 'O')
            ->assertSet('movesCount', 1);
    }

    public function test_cannot_move_on_occupied_cell(): void
    {
        Livewire::test(TicTacToe::class)
            ->call('makeMove', 0)
            ->assertSet('board.0', 'X')
            ->call('makeMove', 0)
            ->assertSet('board.0', 'X') // Still X, not changed to O
            ->assertSet('movesCount', 1); // Count didn't increment
    }

    public function test_detects_horizontal_win(): void
    {
        Livewire::test(TicTacToe::class)
            ->set('board', ['X', 'X', null, null, null, null, null, null, null])
            ->set('currentPlayer', 'X')
            ->call('makeMove', 2)
            ->assertSet('winner', 'X');
    }

    public function test_detects_vertical_win(): void
    {
        Livewire::test(TicTacToe::class)
            ->set('board', ['X', null, null, 'X', null, null, null, null, null])
            ->set('currentPlayer', 'X')
            ->call('makeMove', 6)
            ->assertSet('winner', 'X');
    }

    public function test_detects_diagonal_win(): void
    {
        Livewire::test(TicTacToe::class)
            ->set('board', ['X', null, null, null, 'X', null, null, null, null])
            ->set('currentPlayer', 'X')
            ->call('makeMove', 8)
            ->assertSet('winner', 'X');
    }

    public function test_detects_draw(): void
    {
        Livewire::test(TicTacToe::class)
            ->set('board', ['X', 'O', 'X', 'X', 'O', 'O', 'O', 'X', null])
            ->set('currentPlayer', 'X')
            ->call('makeMove', 8)
            ->assertSet('isDraw', true)
            ->assertSet('winner', null);
    }

    public function test_cannot_move_after_game_over(): void
    {
        Livewire::test(TicTacToe::class)
            ->set('board', ['X', 'X', 'X', null, null, null, null, null, null])
            ->set('winner', 'X')
            ->call('makeMove', 3)
            ->assertSet('board.3', null) // Move was rejected
            ->assertSet('movesCount', 0);
    }

    public function test_can_reset_game(): void
    {
        Livewire::test(TicTacToe::class)
            ->call('makeMove', 0)
            ->call('makeMove', 1)
            ->call('newGame')
            ->assertSet('board', array_fill(0, 9, null))
            ->assertSet('currentPlayer', 'X')
            ->assertSet('winner', null)
            ->assertSet('movesCount', 0);
    }

    public function test_ai_mode_can_be_set(): void
    {
        Livewire::test(TicTacToe::class)
            ->call('setGameMode', 'ai-easy', 'X')
            ->assertSet('gameMode', 'ai-easy')
            ->assertSet('playerSymbol', 'X');
    }

    public function test_game_view_is_accessible(): void
    {
        $response = $this->get(route('games.play', $this->game));

        $response->assertStatus(200);
        $response->assertSee('Tic-Tac-Toe');
    }
}
