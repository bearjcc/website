<?php

declare(strict_types=1);

namespace Tests\Unit\Games;

use App\Games\Connect4\Connect4Engine;
use App\Games\Connect4\Connect4Game;
use PHPUnit\Framework\TestCase;

class Connect4EngineTest extends TestCase
{
    public function test_initial_state(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $this->assertEquals(6, count($state['board'])); // 6 rows
        $this->assertEquals(7, count($state['board'][0])); // 7 columns
        $this->assertEquals(Connect4Engine::RED, $state['currentPlayer']);
        $this->assertFalse($state['gameOver']);
        $this->assertNull($state['winner']);
        $this->assertEquals(0, $state['moves']);
        $this->assertEquals(['red' => 0, 'yellow' => 0], $state['score']);
    }

    public function test_drop_piece_in_empty_column(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $newState = $engine->applyMove($state, ['column' => 3]);

        $this->assertEquals(Connect4Engine::RED, $newState['board'][5][3]); // Bottom row
        $this->assertEquals(Connect4Engine::YELLOW, $newState['currentPlayer']);
        $this->assertEquals(1, $newState['moves']);
        $this->assertFalse($newState['gameOver']);
    }

    public function test_drop_piece_stacks_upward(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Drop two pieces in same column
        $state1 = $engine->applyMove($state, ['column' => 3]);
        $state2 = $engine->applyMove($state1, ['column' => 3]);

        $this->assertEquals(Connect4Engine::RED, $state2['board'][5][3]); // Bottom
        $this->assertEquals(Connect4Engine::YELLOW, $state2['board'][4][3]); // Above bottom
        $this->assertEquals(2, $state2['moves']);
    }

    public function test_cannot_drop_in_full_column(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Fill column 3
        for ($i = 0; $i < 6; $i++) {
            $state = $engine->applyMove($state, ['column' => 3]);
        }

        // Try to drop one more - should not change
        $finalState = $engine->applyMove($state, ['column' => 3]);

        $this->assertEquals($state, $finalState);
    }

    public function test_horizontal_win(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Red gets row 5 cols 0,1,2,3. Yellow plays elsewhere.
        $state = $engine->applyMove($state, ['column' => 0]); // Red
        $state = $engine->applyMove($state, ['column' => 4]); // Yellow
        $state = $engine->applyMove($state, ['column' => 1]); // Red
        $state = $engine->applyMove($state, ['column' => 5]); // Yellow
        $state = $engine->applyMove($state, ['column' => 2]); // Red
        $state = $engine->applyMove($state, ['column' => 6]); // Yellow
        $state = $engine->applyMove($state, ['column' => 3]); // Red - wins horizontal

        $this->assertTrue($state['gameOver']);
        $this->assertEquals(Connect4Engine::RED, $state['winner']);
        $this->assertNotNull($state['winningLine']);
    }

    public function test_vertical_win(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Red gets 4 in column 3; Yellow plays other columns
        $state = $engine->applyMove($state, ['column' => 3]); // Red
        $state = $engine->applyMove($state, ['column' => 0]); // Yellow
        $state = $engine->applyMove($state, ['column' => 3]); // Red
        $state = $engine->applyMove($state, ['column' => 1]); // Yellow
        $state = $engine->applyMove($state, ['column' => 3]); // Red
        $state = $engine->applyMove($state, ['column' => 2]); // Yellow
        $state = $engine->applyMove($state, ['column' => 3]); // Red - wins vertical

        $this->assertTrue($state['gameOver']);
        $this->assertEquals(Connect4Engine::RED, $state['winner']);
        $this->assertNotNull($state['winningLine']);
    }

    public function test_diagonal_win(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Build diagonal (5,0)-(4,1)-(3,2)-(2,3)-(1,4): Red needs col 4 drop to complete
        // Yellow fills col 4 rows 5,4,3,2 so Red's drop lands at (1,4)
        $state['board'][5][0] = Connect4Engine::RED;
        $state['board'][4][1] = Connect4Engine::RED;
        $state['board'][3][2] = Connect4Engine::RED;
        $state['board'][2][3] = Connect4Engine::RED;
        $state['board'][5][4] = Connect4Engine::YELLOW;
        $state['board'][4][4] = Connect4Engine::YELLOW;
        $state['board'][3][4] = Connect4Engine::YELLOW;
        $state['board'][2][4] = Connect4Engine::YELLOW;
        $state['currentPlayer'] = Connect4Engine::RED;
        $state['moves'] = 8;

        $newState = $engine->applyMove($state, ['column' => 4]);

        $this->assertTrue($newState['gameOver']);
        $this->assertEquals(Connect4Engine::RED, $newState['winner']);
        $this->assertNotNull($newState['winningLine']);
    }

    public function test_draw_game(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Verified draw sequence (found via random search)
        $moves = [3, 1, 2, 6, 0, 4, 1, 2, 5, 2, 5, 6, 3, 1, 2, 4, 6, 2, 1, 3, 4, 6, 1, 0, 4, 5, 2, 1, 4, 3, 3, 4, 3, 5, 0, 5, 5, 6, 6, 0, 0, 0];

        foreach ($moves as $column) {
            $state = $engine->applyMove($state, ['column' => $column]);
        }

        $this->assertTrue($state['gameOver']);
        $this->assertEquals('draw', $state['winner']);
    }

    public function test_validate_move_valid(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $this->assertTrue($engine->validateMove($state, ['column' => 3]));
        $this->assertTrue($engine->validateMove($state, ['column' => 0]));
        $this->assertTrue($engine->validateMove($state, ['column' => 6]));
    }

    public function test_validate_move_invalid_column(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $this->assertFalse($engine->validateMove($state, ['column' => -1]));
        $this->assertFalse($engine->validateMove($state, ['column' => 7]));
        $this->assertFalse($engine->validateMove($state, ['column' => 10]));
    }

    public function test_validate_move_full_column(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Fill column 3
        for ($i = 0; $i < 6; $i++) {
            $state = $engine->applyMove($state, ['column' => 3]);
        }

        $this->assertFalse($engine->validateMove($state, ['column' => 3]));
    }

    public function test_validate_move_game_over(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();
        $state['gameOver'] = true;

        $this->assertFalse($engine->validateMove($state, ['column' => 3]));
    }

    public function test_can_drop_in_column(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $this->assertTrue(Connect4Engine::canDropInColumn($state, 0));
        $this->assertTrue(Connect4Engine::canDropInColumn($state, 6));

        // Fill column 3
        for ($i = 0; $i < 6; $i++) {
            $state = $engine->applyMove($state, ['column' => 3]);
        }

        $this->assertFalse(Connect4Engine::canDropInColumn($state, 3));
    }

    public function test_score_tracking(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Red wins with vertical 4 in column 3
        $state = $engine->applyMove($state, ['column' => 3]); // Red
        $state = $engine->applyMove($state, ['column' => 0]); // Yellow
        $state = $engine->applyMove($state, ['column' => 3]); // Red
        $state = $engine->applyMove($state, ['column' => 1]); // Yellow
        $state = $engine->applyMove($state, ['column' => 3]); // Red
        $state = $engine->applyMove($state, ['column' => 2]); // Yellow
        $newState = $engine->applyMove($state, ['column' => 3]); // Red wins

        $this->assertEquals(['red' => 1, 'yellow' => 0], $newState['score']);
    }

    public function test_winning_line_detection(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Red completes horizontal win with final move at (5,3)
        $state = $engine->applyMove($state, ['column' => 0]); // Red
        $state = $engine->applyMove($state, ['column' => 4]); // Yellow
        $state = $engine->applyMove($state, ['column' => 1]); // Red
        $state = $engine->applyMove($state, ['column' => 5]); // Yellow
        $state = $engine->applyMove($state, ['column' => 2]); // Red
        $state = $engine->applyMove($state, ['column' => 6]); // Yellow
        $newState = $engine->applyMove($state, ['column' => 3]); // Red wins horizontal

        $this->assertNotNull($newState['winningLine']);
        $this->assertCount(4, $newState['winningLine']);
    }

    public function test_empty_board_no_moves(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $this->assertFalse($engine->isOver($state));
    }

    public function test_alternating_turns(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $state1 = $engine->applyMove($state, ['column' => 0]);
        $this->assertEquals(Connect4Engine::YELLOW, $state1['currentPlayer']);

        $state2 = $engine->applyMove($state1, ['column' => 1]);
        $this->assertEquals(Connect4Engine::RED, $state2['currentPlayer']);
    }

    public function test_move_validation_requires_column(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        $this->assertFalse($engine->validateMove($state, []));
        $this->assertFalse($engine->validateMove($state, ['row' => 5]));
    }

    public function test_game_rules(): void
    {
        $engine = new Connect4Game();
        $rules = $engine->rules();

        $this->assertIsArray($rules);
        $this->assertNotEmpty($rules);
        $this->assertStringContainsString('4 pieces in a row', $rules[2]);
    }

    public function test_engine_static_methods(): void
    {
        $state = Connect4Engine::initialState();

        $this->assertEquals(6, count($state['board']));
        $this->assertEquals(7, count($state['board'][0]));
        $this->assertEquals(Connect4Engine::RED, $state['currentPlayer']);
        $this->assertFalse($state['gameOver']);
    }

    public function test_valid_moves(): void
    {
        $state = Connect4Engine::initialState();
        $validMoves = Connect4Engine::getValidMoves($state);

        $this->assertEquals([0, 1, 2, 3, 4, 5, 6], $validMoves);

        // Fill column 3
        for ($i = 0; $i < 6; $i++) {
            $state = Connect4Engine::dropPiece($state, 3);
        }

        $validMoves = Connect4Engine::getValidMoves($state);

        $this->assertEquals([0, 1, 2, 4, 5, 6], $validMoves);
    }
}
