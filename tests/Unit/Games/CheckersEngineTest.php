<?php

declare(strict_types=1);

namespace Tests\Unit\Games;

use App\Games\Checkers\CheckersEngine;
use PHPUnit\Framework\TestCase;

class CheckersEngineTest extends TestCase
{
    public function test_initial_state_invariants(): void
    {
        $state = CheckersEngine::initialState();

        $this->assertCount(CheckersEngine::BOARD_SIZE, $state['board']);
        $this->assertCount(CheckersEngine::BOARD_SIZE, $state['board'][0]);
        $this->assertSame(CheckersEngine::RED, $state['currentPlayer']);
        $this->assertFalse($state['gameOver']);
        $this->assertNull($state['winner']);
        $this->assertSame(0, $state['moves']);
        $this->assertIsArray($state['moveHistory']);
        $this->assertEmpty($state['moveHistory']);
        $this->assertSame([CheckersEngine::RED => 0, CheckersEngine::BLACK => 0], $state['capturedPieces']);
    }

    public function test_get_piece_display_name(): void
    {
        $this->assertSame('Red', CheckersEngine::getPieceDisplayName(CheckersEngine::RED));
        $this->assertSame('Black', CheckersEngine::getPieceDisplayName(CheckersEngine::BLACK));
        $this->assertSame('Red King', CheckersEngine::getPieceDisplayName(CheckersEngine::RED_KING));
        $this->assertSame('Black King', CheckersEngine::getPieceDisplayName(CheckersEngine::BLACK_KING));
        $this->assertSame('Empty', CheckersEngine::getPieceDisplayName(null));
    }

    public function test_is_valid_position(): void
    {
        $this->assertTrue(CheckersEngine::isValidPosition(0, 0));
        $this->assertTrue(CheckersEngine::isValidPosition(7, 7));
        $this->assertFalse(CheckersEngine::isValidPosition(-1, 0));
        $this->assertFalse(CheckersEngine::isValidPosition(0, 8));
    }

    public function test_opening_has_regular_moves_for_current_player(): void
    {
        $state = CheckersEngine::initialState();
        $moves = CheckersEngine::getValidMoves($state);

        $this->assertNotEmpty($moves, 'red should have at least one step from the standard opening');

        $hasStep = false;
        foreach ($moves as $move) {
            if ($move['type'] === 'move') {
                $hasStep = true;
                $this->assertSame(1, $move['to']['row'] - $move['from']['row'], 'red advances one row at a time: '.json_encode($move));
            }
        }
        $this->assertTrue($hasStep, 'opening should include a simple (non-capture) move');
    }

    public function test_make_move_switches_current_player_on_simple_step(): void
    {
        $state = CheckersEngine::initialState();
        $stepMove = $this->firstStepMove($state);
        $this->assertIsArray($stepMove);

        $newState = CheckersEngine::makeMove($state, $stepMove);

        $this->assertSame(CheckersEngine::BLACK, $newState['currentPlayer']);
        $this->assertSame(1, $newState['moves']);
    }

    public function test_make_move_does_not_clear_source_square_in_original_state_array(): void
    {
        $state = CheckersEngine::initialState();
        $stepMove = $this->firstStepMove($state);
        $this->assertIsArray($stepMove);

        $from = $stepMove['from'];
        $piece = $state['board'][$from['row']][$from['col']];
        $this->assertNotNull($piece);

        CheckersEngine::makeMove($state, $stepMove);

        $this->assertSame($piece, $state['board'][$from['row']][$from['col']], 'source board must not be mutated in place by makeMove');
    }

    public function test_get_stats_initial_state(): void
    {
        $state = CheckersEngine::initialState();
        $stats = CheckersEngine::getStats($state);

        $this->assertSame(0, $stats['moves']);
        $this->assertSame(CheckersEngine::RED, $stats['currentPlayer']);
        $this->assertSame(12, $stats['redPieces']);
        $this->assertSame(12, $stats['blackPieces']);
        $this->assertArrayHasKey('capturedPieces', $stats);
        $this->assertSame(0, $stats['capturedPieces'][CheckersEngine::RED]);
        $this->assertSame(0, $stats['capturedPieces'][CheckersEngine::BLACK]);
        $this->assertGreaterThan(0, $stats['validMoves']);
    }

    public function test_after_red_step_black_simple_moves_advance_toward_red(): void
    {
        $afterRed = CheckersEngine::makeMove(
            CheckersEngine::initialState(),
            $this->firstStepMoveOrFail(CheckersEngine::initialState())
        );

        $this->assertSame(CheckersEngine::BLACK, $afterRed['currentPlayer']);
        $moves = CheckersEngine::getValidMoves($afterRed);
        $this->assertNotEmpty($moves);

        foreach ($moves as $move) {
            if ($move['type'] === 'move') {
                $this->assertSame(
                    -1,
                    $move['to']['row'] - $move['from']['row'],
                    'black man should step one row toward red (decreasing row index): '.json_encode($move)
                );
            }
        }
    }

    public function test_red_man_promotes_to_king_reaching_back_row(): void
    {
        $state = $this->stateWithScatteredPieces([
            [6, 1, CheckersEngine::RED],
            [0, 1, CheckersEngine::BLACK], // both sides have a piece; far from the promotion square
        ], CheckersEngine::RED);
        $this->assertSame(CheckersEngine::RED, $state['board'][6][1]);

        $newState = CheckersEngine::makeMove($state, [
            'from' => ['row' => 6, 'col' => 1],
            'to' => ['row' => 7, 'col' => 0],
            'captures' => [],
            'type' => 'move',
        ]);

        $this->assertSame(CheckersEngine::RED_KING, $newState['board'][7][0]);
    }

    public function test_black_man_promotes_to_king_reaching_back_row(): void
    {
        $state = $this->stateWithScatteredPieces([
            [1, 0, CheckersEngine::BLACK],
            [7, 0, CheckersEngine::RED],
        ], CheckersEngine::BLACK);

        $newState = CheckersEngine::makeMove($state, [
            'from' => ['row' => 1, 'col' => 0],
            'to' => ['row' => 0, 'col' => 1],
            'captures' => [],
            'type' => 'move',
        ]);

        $this->assertSame(CheckersEngine::BLACK_KING, $newState['board'][0][1]);
    }

    public function test_red_king_exposes_all_four_diagonal_step_directions(): void
    {
        // Use a dark square; engine only enumerates dark cells for piece moves.
        $state = $this->stateWithScatteredPieces([
            [4, 1, CheckersEngine::RED_KING],
            [0, 1, CheckersEngine::BLACK],
        ], CheckersEngine::RED);
        $moves = CheckersEngine::getValidMoves($state);
        $deltas = [];
        foreach ($moves as $move) {
            if ($move['type'] === 'move' && $move['from']['row'] === 4 && $move['from']['col'] === 1) {
                $deltas[] = [$move['to']['row'] - 4, $move['to']['col'] - 1];
            }
        }
        sort($deltas);
        $this->assertSame([[-1, -1], [-1, 1], [1, -1], [1, 1]], $deltas);
    }

    /**
     * @return array<string, mixed>
     */
    private function stateWithScatteredPieces(array $placements, string $currentPlayer): array
    {
        $state = CheckersEngine::initialState();
        for ($r = 0; $r < CheckersEngine::BOARD_SIZE; $r++) {
            for ($c = 0; $c < CheckersEngine::BOARD_SIZE; $c++) {
                $state['board'][$r][$c] = CheckersEngine::EMPTY;
            }
        }
        foreach ($placements as $placement) {
            [$row, $col, $piece] = $placement;
            $state['board'][$row][$col] = $piece;
        }
        $state['currentPlayer'] = $currentPlayer;
        $state['moves'] = 0;
        $state['moveHistory'] = [];
        $state['lastMove'] = null;
        $state['capturedPieces'] = [CheckersEngine::RED => 0, CheckersEngine::BLACK => 0];
        $state['mustJump'] = false;
        $state['jumpSequence'] = [];
        $state['selectedSquare'] = null;
        $state['gameOver'] = false;
        $state['winner'] = null;

        return $state;
    }

    /**
     * @return array<string, mixed>
     */
    private function firstStepMoveOrFail(array $state): array
    {
        $m = $this->firstStepMove($state);
        $this->assertIsArray($m);

        return $m;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function firstStepMove(array $state): ?array
    {
        foreach (CheckersEngine::getValidMoves($state) as $move) {
            if ($move['type'] === 'move') {
                return $move;
            }
        }

        return null;
    }
}
