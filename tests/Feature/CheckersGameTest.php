<?php

namespace Tests\Feature;

use App\Games\Checkers\CheckersEngine;
use App\Games\Checkers\CheckersGame;
use App\Livewire\Games\Checkers;
use Livewire\Livewire;
use Tests\TestCase;

class CheckersGameTest extends TestCase
{
    public function test_checkers_game_can_be_created(): void
    {
        $game = new CheckersGame();

        $this->assertEquals('checkers', $game->slug());
        $this->assertEquals('Checkers', $game->name());
        $this->assertNotEmpty($game->description());
        $this->assertNotEmpty($game->rules());
    }

    public function test_checkers_engine_initializes_correctly(): void
    {
        $state = CheckersEngine::initialState();

        $this->assertArrayHasKey('board', $state);
        $this->assertArrayHasKey('currentPlayer', $state);
        $this->assertArrayHasKey('gameOver', $state);

        $this->assertEquals('red', $state['currentPlayer']);
        $this->assertFalse($state['gameOver']);
        $this->assertCount(8, $state['board']);
        $this->assertCount(8, $state['board'][0]);
    }

    public function test_checkers_board_has_correct_initial_setup(): void
    {
        $state = CheckersEngine::initialState();

        // Check that red pieces are in bottom 3 rows on dark squares
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 8; $col++) {
                if (CheckersEngine::isDarkSquare($row, $col)) {
                    $this->assertEquals(CheckersEngine::RED, $state['board'][$row][$col]);
                }
            }
        }

        // Check that black pieces are in top 3 rows on dark squares
        for ($row = 5; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                if (CheckersEngine::isDarkSquare($row, $col)) {
                    $this->assertEquals(CheckersEngine::BLACK, $state['board'][$row][$col]);
                }
            }
        }

        // Check that middle rows are empty
        for ($row = 3; $row < 5; $row++) {
            for ($col = 0; $col < 8; $col++) {
                $this->assertNull($state['board'][$row][$col]);
            }
        }
    }

    public function test_checkers_initial_board_has_pieces(): void
    {
        $state = CheckersEngine::initialState();

        // Count red pieces (should be 12)
        $redCount = 0;
        for ($row = 0; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                if (CheckersEngine::isDarkSquare($row, $col)) {
                    $piece = $state['board'][$row][$col];
                    if (CheckersEngine::isPlayerPiece($piece, CheckersEngine::RED)) {
                        $redCount++;
                    }
                }
            }
        }

        $this->assertEquals(12, $redCount);

        // Count black pieces (should be 12)
        $blackCount = 0;
        for ($row = 0; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                if (CheckersEngine::isDarkSquare($row, $col)) {
                    $piece = $state['board'][$row][$col];
                    if (CheckersEngine::isPlayerPiece($piece, CheckersEngine::BLACK)) {
                        $blackCount++;
                    }
                }
            }
        }

        $this->assertEquals(12, $blackCount);
    }

    public function test_checkers_get_valid_moves_function(): void
    {
        $state = CheckersEngine::initialState();
        $validMoves = CheckersEngine::getValidMoves($state);

        // The function should return an array (even if empty)
        $this->assertIsArray($validMoves);

        // Each move should have the correct structure
        foreach ($validMoves as $move) {
            $this->assertArrayHasKey('from', $move);
            $this->assertArrayHasKey('to', $move);
            $this->assertArrayHasKey('captures', $move);
            $this->assertArrayHasKey('type', $move);

            // From and to should have row and col
            $this->assertArrayHasKey('row', $move['from']);
            $this->assertArrayHasKey('col', $move['from']);
            $this->assertArrayHasKey('row', $move['to']);
            $this->assertArrayHasKey('col', $move['to']);

            // Type should be either 'move' or 'jump'
            $this->assertContains($move['type'], ['move', 'jump']);
        }
    }

    private function callPrivateMethod($className, $methodName, $args = [])
    {
        $reflection = new \ReflectionClass($className);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs(null, $args);
    }

    public function test_checkers_dark_squares_identification(): void
    {
        // Test some known dark squares
        $this->assertTrue(CheckersEngine::isDarkSquare(0, 1));
        $this->assertTrue(CheckersEngine::isDarkSquare(1, 0));
        $this->assertTrue(CheckersEngine::isDarkSquare(2, 3));
        $this->assertTrue(CheckersEngine::isDarkSquare(7, 6));

        // Test some known light squares
        $this->assertTrue(CheckersEngine::isLightSquare(0, 0));
        $this->assertTrue(CheckersEngine::isLightSquare(1, 1));
        $this->assertTrue(CheckersEngine::isLightSquare(2, 2));
        $this->assertTrue(CheckersEngine::isLightSquare(7, 7));
    }

    public function test_checkers_piece_ownership(): void
    {
        $this->assertTrue(CheckersEngine::isPlayerPiece(CheckersEngine::RED, CheckersEngine::RED));
        $this->assertTrue(CheckersEngine::isPlayerPiece(CheckersEngine::BLACK, CheckersEngine::BLACK));
        $this->assertTrue(CheckersEngine::isPlayerPiece(CheckersEngine::RED_KING, CheckersEngine::RED));
        $this->assertTrue(CheckersEngine::isPlayerPiece(CheckersEngine::BLACK_KING, CheckersEngine::BLACK));

        $this->assertFalse(CheckersEngine::isPlayerPiece(CheckersEngine::RED, CheckersEngine::BLACK));
        $this->assertFalse(CheckersEngine::isPlayerPiece(CheckersEngine::BLACK, CheckersEngine::RED));
        $this->assertFalse(CheckersEngine::isPlayerPiece(null, CheckersEngine::RED));
    }

    public function test_checkers_move_validation(): void
    {
        $state = CheckersEngine::initialState();
        $game = new CheckersGame();

        // Test invalid move (off board)
        $invalidMove = [
            'from' => ['row' => -1, 'col' => 0],
            'to' => ['row' => 0, 'col' => 1]
        ];
        $this->assertFalse($game->validateMove($state, $invalidMove));

        // Test move from empty square
        $emptyMove = [
            'from' => ['row' => 3, 'col' => 0],
            'to' => ['row' => 4, 'col' => 1]
        ];
        $this->assertFalse($game->validateMove($state, $emptyMove));
    }

    public function test_checkers_king_detection(): void
    {
        $this->assertTrue(CheckersEngine::isKing(CheckersEngine::RED_KING));
        $this->assertTrue(CheckersEngine::isKing(CheckersEngine::BLACK_KING));
        $this->assertFalse(CheckersEngine::isKing(CheckersEngine::RED));
        $this->assertFalse(CheckersEngine::isKing(CheckersEngine::BLACK));
        $this->assertFalse(CheckersEngine::isKing(null));
    }

    public function test_engine_creates_valid_initial_state(): void
    {
        $state = CheckersEngine::initialState();

        // Check board dimensions
        $this->assertCount(8, $state['board']);
        $this->assertCount(8, $state['board'][0]);

        // Check initial player
        $this->assertEquals('red', $state['currentPlayer']);

        // Check game not over
        $this->assertFalse($state['gameOver']);
        $this->assertNull($state['winner']);

        // Check move tracking
        $this->assertEquals(0, $state['moves']);
        $this->assertIsArray($state['moveHistory']);
        $this->assertEmpty($state['moveHistory']);
    }

    public function test_game_interface_implementation(): void
    {
        $game = new CheckersGame();

        $this->assertEquals('checkers', $game->id());
        $this->assertEquals('Checkers', $game->name());
        $this->assertEquals('checkers', $game->slug());
        $this->assertNotEmpty($game->description());
        $this->assertNotEmpty($game->rules());

        $initialState = $game->newGameState();
        $this->assertFalse($game->isOver($initialState));
    }
}
