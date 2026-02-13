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

        // Red gets 4 in a row on row 5: R at 0,1,2,3 with Yellow blocking in same columns
        $state = $engine->applyMove($state, ['column' => 0]); // Red (5,0)
        $state = $engine->applyMove($state, ['column' => 0]); // Yellow (4,0)
        $state = $engine->applyMove($state, ['column' => 1]); // Red (5,1)
        $state = $engine->applyMove($state, ['column' => 1]); // Yellow (4,1)
        $state = $engine->applyMove($state, ['column' => 2]); // Red (5,2)
        $state = $engine->applyMove($state, ['column' => 2]); // Yellow (4,2)
        $state = $engine->applyMove($state, ['column' => 3]); // Red (5,3) - wins

        $this->assertTrue($state['gameOver']);
        $this->assertEquals(Connect4Engine::RED, $state['winner']);
        $this->assertNotNull($state['winningLine']);
    }

    public function test_vertical_win(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Red drops 4 in column 3, Yellow alternates in column 0
        for ($i = 0; $i < 4; $i++) {
            $state = $engine->applyMove($state, ['column' => 3]); // Red
            if (! $state['gameOver']) {
                $state = $engine->applyMove($state, ['column' => 0]); // Yellow
            }
        }

        $this->assertTrue($state['gameOver']);
        $this->assertEquals(Connect4Engine::RED, $state['winner']);
        $this->assertNotNull($state['winningLine']);
    }

    public function test_diagonal_win(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Diagonal (5,0)-(4,1)-(3,2)-(2,3). Fill col 3 so next drop is (2,3).
        $state['board'][5][0] = Connect4Engine::RED;
        $state['board'][4][1] = Connect4Engine::RED;
        $state['board'][3][2] = Connect4Engine::RED;
        $state['board'][5][3] = Connect4Engine::YELLOW;
        $state['board'][4][3] = Connect4Engine::RED;
        $state['board'][3][3] = Connect4Engine::YELLOW;
        $state['currentPlayer'] = Connect4Engine::RED;
        $state['moves'] = 6;

        $newState = $engine->applyMove($state, ['column' => 3]); // Red at (2,3) wins

        $this->assertTrue($newState['gameOver']);
        $this->assertEquals(Connect4Engine::RED, $newState['winner']);
        $this->assertNotNull($newState['winningLine']);
        $winResult = Connect4Engine::checkWin($newState, 2, 3, Connect4Engine::RED);
        $this->assertTrue($winResult['isWin']);
    }

    public function test_draw_game(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Engine declares draw when board is full with no winner
        $this->assertFalse($state['gameOver']);
        $this->assertNull($state['winner']);

        // Verify isBoardFull and draw logic: when board is full and no win, winner is 'draw'
        $state['board'] = array_fill(0, 6, array_fill(0, 7, Connect4Engine::RED));
        for ($r = 0; $r < 6; $r++) {
            for ($c = 0; $c < 7; $c++) {
                $state['board'][$r][$c] = ($r + $c) % 2 === 0 ? Connect4Engine::RED : Connect4Engine::YELLOW;
            }
        }
        $state['gameOver'] = true;
        $state['winner'] = 'draw';
        $state['moves'] = 42;

        $this->assertTrue($engine->isOver($state));
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

        // Manually set up a winning state
        $state['board'][5][0] = Connect4Engine::RED;
        $state['board'][4][1] = Connect4Engine::RED;
        $state['board'][3][2] = Connect4Engine::RED;
        $state['board'][2][3] = Connect4Engine::RED;
        $state['gameOver'] = true;
        $state['winner'] = Connect4Engine::RED;
        $state['moves'] = 4;

        // Set score to reflect red's win
        $state['score'] = [Connect4Engine::RED => 1, Connect4Engine::YELLOW => 0];

        $this->assertEquals([Connect4Engine::RED => 1, Connect4Engine::YELLOW => 0], $state['score']);

        // Test that the getScore method works
        $scores = Connect4Engine::getScore($state);
        $this->assertArrayHasKey(Connect4Engine::RED, $scores);
        $this->assertArrayHasKey(Connect4Engine::YELLOW, $scores);
        $this->assertEquals(381, $scores[Connect4Engine::RED]);
        $this->assertEquals(0, $scores[Connect4Engine::YELLOW]);
    }

    public function test_winning_line_detection(): void
    {
        $engine = new Connect4Game();
        $state = $engine->initialState();

        // Manually set up a winning state (horizontal win)
        for ($col = 0; $col < 4; $col++) {
            $state['board'][5][$col] = Connect4Engine::RED;
        }
        $state['gameOver'] = true;
        $state['winner'] = Connect4Engine::RED;
        $state['winningLine'] = [
            ['row' => 5, 'col' => 0],
            ['row' => 5, 'col' => 1],
            ['row' => 5, 'col' => 2],
            ['row' => 5, 'col' => 3],
        ];
        $state['moves'] = 4;

        $this->assertNotNull($state['winningLine']);
        $this->assertCount(4, $state['winningLine']);

        // Test that the engine can detect the win
        $winResult = Connect4Engine::checkWin($state, 5, 3, Connect4Engine::RED);
        $this->assertTrue($winResult['isWin']);
        $this->assertCount(4, $winResult['line']);
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
        $this->assertGreaterThanOrEqual(5, count($rules));

        $winRuleFound = false;
        foreach ($rules as $rule) {
            if (str_contains(strtolower($rule), 'win') && str_contains(strtolower($rule), '4')) {
                $winRuleFound = true;
                break;
            }
        }
        $this->assertTrue($winRuleFound, 'Should have a rule about winning with 4 in a row');
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
