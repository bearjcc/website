<?php

namespace Tests\Feature;

use App\Games\Chess\ChessEngine;
use App\Games\Chess\ChessGame;
use Tests\TestCase;

class ChessGameTest extends TestCase
{
    public function test_chess_game_can_be_created(): void
    {
        $game = new ChessGame();

        $this->assertEquals('chess', $game->slug());
        $this->assertEquals('Chess', $game->name());
        $this->assertNotEmpty($game->description());
        $this->assertNotEmpty($game->rules());
    }

    public function test_chess_engine_initializes_correctly(): void
    {
        $state = ChessEngine::initialState();

        $this->assertArrayHasKey('board', $state);
        $this->assertArrayHasKey('currentPlayer', $state);
        $this->assertArrayHasKey('gameOver', $state);

        $this->assertEquals('white', $state['currentPlayer']);
        $this->assertFalse($state['gameOver']);
        $this->assertCount(8, $state['board']);
        $this->assertCount(8, $state['board'][0]);
    }

    public function test_chess_board_has_correct_initial_setup(): void
    {
        $state = ChessEngine::initialState();

        // Check white pieces (bottom rows)
        $this->assertEquals(ChessEngine::WHITE_ROOK, $state['board'][7][0]);
        $this->assertEquals(ChessEngine::WHITE_KNIGHT, $state['board'][7][1]);
        $this->assertEquals(ChessEngine::WHITE_BISHOP, $state['board'][7][2]);
        $this->assertEquals(ChessEngine::WHITE_QUEEN, $state['board'][7][3]);
        $this->assertEquals(ChessEngine::WHITE_KING, $state['board'][7][4]);
        $this->assertEquals(ChessEngine::WHITE_BISHOP, $state['board'][7][5]);
        $this->assertEquals(ChessEngine::WHITE_KNIGHT, $state['board'][7][6]);
        $this->assertEquals(ChessEngine::WHITE_ROOK, $state['board'][7][7]);

        // Check white pawns
        for ($col = 0; $col < 8; $col++) {
            $this->assertEquals(ChessEngine::WHITE_PAWN, $state['board'][6][$col]);
        }

        // Check black pieces (top rows)
        $this->assertEquals(ChessEngine::BLACK_ROOK, $state['board'][0][0]);
        $this->assertEquals(ChessEngine::BLACK_KNIGHT, $state['board'][0][1]);
        $this->assertEquals(ChessEngine::BLACK_BISHOP, $state['board'][0][2]);
        $this->assertEquals(ChessEngine::BLACK_QUEEN, $state['board'][0][3]);
        $this->assertEquals(ChessEngine::BLACK_KING, $state['board'][0][4]);
        $this->assertEquals(ChessEngine::BLACK_BISHOP, $state['board'][0][5]);
        $this->assertEquals(ChessEngine::BLACK_KNIGHT, $state['board'][0][6]);
        $this->assertEquals(ChessEngine::BLACK_ROOK, $state['board'][0][7]);

        // Check black pawns
        for ($col = 0; $col < 8; $col++) {
            $this->assertEquals(ChessEngine::BLACK_PAWN, $state['board'][1][$col]);
        }

        // Check middle rows are empty
        for ($row = 2; $row < 6; $row++) {
            for ($col = 0; $col < 8; $col++) {
                $this->assertNull($state['board'][$row][$col]);
            }
        }
    }

    public function test_chess_piece_ownership(): void
    {
        $this->assertTrue(ChessEngine::isPlayerPiece(ChessEngine::WHITE_KING, ChessEngine::WHITE));
        $this->assertTrue(ChessEngine::isPlayerPiece(ChessEngine::BLACK_KING, ChessEngine::BLACK));
        $this->assertTrue(ChessEngine::isPlayerPiece(ChessEngine::WHITE_PAWN, ChessEngine::WHITE));
        $this->assertTrue(ChessEngine::isPlayerPiece(ChessEngine::BLACK_PAWN, ChessEngine::BLACK));

        $this->assertFalse(ChessEngine::isPlayerPiece(ChessEngine::WHITE_KING, ChessEngine::BLACK));
        $this->assertFalse(ChessEngine::isPlayerPiece(ChessEngine::BLACK_KING, ChessEngine::WHITE));
        $this->assertFalse(ChessEngine::isPlayerPiece(null, ChessEngine::WHITE));
    }

    public function test_chess_king_detection(): void
    {
        $this->assertTrue(ChessEngine::isKing(ChessEngine::WHITE_KING));
        $this->assertTrue(ChessEngine::isKing(ChessEngine::BLACK_KING));
        $this->assertFalse(ChessEngine::isKing(ChessEngine::WHITE_PAWN));
        $this->assertFalse(ChessEngine::isKing(ChessEngine::BLACK_PAWN));
        $this->assertFalse(ChessEngine::isKing(null));
    }

    public function test_chess_get_valid_moves_function(): void
    {
        $state = ChessEngine::initialState();
        $validMoves = ChessEngine::getValidMoves($state);

        // The function should return an array (even if empty in initial position)
        $this->assertIsArray($validMoves);

        // Each move should have the correct structure
        foreach ($validMoves as $move) {
            $this->assertArrayHasKey('from', $move);
            $this->assertArrayHasKey('to', $move);
            $this->assertArrayHasKey('piece', $move);
            $this->assertArrayHasKey('captured', $move);
            $this->assertArrayHasKey('promotion', $move);
            $this->assertArrayHasKey('special', $move);

            // From and to should have row and col
            $this->assertArrayHasKey('row', $move['from']);
            $this->assertArrayHasKey('col', $move['from']);
            $this->assertArrayHasKey('row', $move['to']);
            $this->assertArrayHasKey('col', $move['to']);
        }
    }

    public function test_chess_move_validation(): void
    {
        $state = ChessEngine::initialState();
        $game = new ChessGame();

        // Test invalid move (off board)
        $invalidMove = [
            'from' => ['row' => -1, 'col' => 0],
            'to' => ['row' => 0, 'col' => 1],
        ];
        $this->assertFalse($game->validateMove($state, $invalidMove));

        // Test move from empty square
        $emptyMove = [
            'from' => ['row' => 3, 'col' => 0],
            'to' => ['row' => 4, 'col' => 1],
        ];
        $this->assertFalse($game->validateMove($state, $emptyMove));

        // Test move from opponent piece
        $opponentMove = [
            'from' => ['row' => 0, 'col' => 0], // Black rook
            'to' => ['row' => 1, 'col' => 0],
        ];
        $this->assertFalse($game->validateMove($state, $opponentMove)); // White's turn
    }

    public function test_chess_algebraic_notation(): void
    {
        // Test position conversion
        $this->assertEquals('a1', ChessEngine::positionToAlgebraic(7, 0));
        $this->assertEquals('h8', ChessEngine::positionToAlgebraic(0, 7));
        $this->assertEquals('e4', ChessEngine::positionToAlgebraic(4, 4));
    }

    public function test_game_interface_implementation(): void
    {
        $game = new ChessGame();

        $this->assertEquals('chess', $game->id());
        $this->assertEquals('Chess', $game->name());
        $this->assertEquals('chess', $game->slug());
        $this->assertNotEmpty($game->description());
        $this->assertNotEmpty($game->rules());

        $initialState = $game->newGameState();
        $this->assertFalse($game->isOver($initialState));
    }
}
