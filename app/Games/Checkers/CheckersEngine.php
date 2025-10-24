<?php

namespace App\Games\Checkers;

/**
 * Checkers game engine
 * Classic strategy game - jump your way to victory!
 */
class CheckersEngine
{
    public const BOARD_SIZE = 8;

    public const EMPTY = null;

    public const RED = 'red';

    public const BLACK = 'black';

    public const RED_KING = 'red_king';

    public const BLACK_KING = 'black_king';

    /**
     * Initialize new game state
     */
    public static function initialState(): array
    {
        $board = self::createInitialBoard();

        return [
            'board' => $board,
            'currentPlayer' => self::RED,
            'gameOver' => false,
            'winner' => null,
            'moves' => 0,
            'moveHistory' => [],
            'lastMove' => null,
            'capturedPieces' => [self::RED => 0, self::BLACK => 0],
            'mustJump' => false,
            'jumpSequence' => [],
            'selectedSquare' => null,
        ];
    }

    /**
     * Create the initial board setup
     */
    private static function createInitialBoard(): array
    {
        $board = array_fill(0, self::BOARD_SIZE, array_fill(0, self::BOARD_SIZE, self::EMPTY));

        // Place red pieces (bottom 3 rows, dark squares only)
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < self::BOARD_SIZE; $col++) {
                if (self::isDarkSquare($row, $col)) {
                    $board[$row][$col] = self::RED;
                }
            }
        }

        // Place black pieces (top 3 rows, dark squares only)
        for ($row = self::BOARD_SIZE - 3; $row < self::BOARD_SIZE; $row++) {
            for ($col = 0; $col < self::BOARD_SIZE; $col++) {
                if (self::isDarkSquare($row, $col)) {
                    $board[$row][$col] = self::BLACK;
                }
            }
        }

        return $board;
    }

    /**
     * Check if a square is dark (where pieces can be placed)
     */
    public static function isDarkSquare(int $row, int $col): bool
    {
        return ($row + $col) % 2 === 1;
    }

    /**
     * Check if a square is light (where pieces cannot be placed)
     */
    public static function isLightSquare(int $row, int $col): bool
    {
        return ($row + $col) % 2 === 0;
    }

    /**
     * Check if a position is valid on the board
     */
    public static function isValidPosition(int $row, int $col): bool
    {
        return $row >= 0 && $row < self::BOARD_SIZE && $col >= 0 && $col < self::BOARD_SIZE;
    }

    /**
     * Get all valid moves for the current player
     */
    public static function getValidMoves(array $state): array
    {
        $moves = [];
        $player = $state['currentPlayer'];

        // Check if player must jump (forced captures)
        $jumpMoves = self::getJumpMoves($state);
        if (!empty($jumpMoves)) {
            return $jumpMoves; // Must jump if possible
        }

        // Regular moves
        for ($row = 0; $row < self::BOARD_SIZE; $row++) {
            for ($col = 0; $col < self::BOARD_SIZE; $col++) {
                if (self::isDarkSquare($row, $col) && self::isPlayerPiece($state['board'][$row][$col], $player)) {
                    $pieceMoves = self::getPieceMoves($state, $row, $col);
                    $moves = array_merge($moves, $pieceMoves);
                }
            }
        }

        return $moves;
    }

    /**
     * Get jump moves (forced captures)
     */
    private static function getJumpMoves(array $state): array
    {
        $moves = [];
        $player = $state['currentPlayer'];

        for ($row = 0; $row < self::BOARD_SIZE; $row++) {
            for ($col = 0; $col < self::BOARD_SIZE; $col++) {
                if (self::isDarkSquare($row, $col) && self::isPlayerPiece($state['board'][$row][$col], $player)) {
                    $pieceJumps = self::getPieceJumps($state, $row, $col);
                    $moves = array_merge($moves, $pieceJumps);
                }
            }
        }

        return $moves;
    }

    /**
     * Get all possible moves for a specific piece
     */
    private static function getPieceMoves(array $state, int $row, int $col): array
    {
        $moves = [];
        $piece = $state['board'][$row][$col];
        $player = $state['currentPlayer'];

        if (!self::isPlayerPiece($piece, $player)) {
            return $moves;
        }

        $directions = self::getPieceDirections($piece);

        foreach ($directions as [$deltaRow, $deltaCol]) {
            $newRow = $row + $deltaRow;
            $newCol = $col + $deltaCol;

            if (self::isValidPosition($newRow, $newCol) && self::isDarkSquare($newRow, $newCol)) {
                // Regular move (empty square)
                if ($state['board'][$newRow][$newCol] === self::EMPTY) {
                    $moves[] = [
                        'from' => ['row' => $row, 'col' => $col],
                        'to' => ['row' => $newRow, 'col' => $newCol],
                        'captures' => [],
                        'type' => 'move'
                    ];
                }
                // Jump move (enemy piece)
                elseif (self::isOpponentPiece($state['board'][$newRow][$newCol], $player)) {
                    $jumpRow = $newRow + $deltaRow;
                    $jumpCol = $newCol + $deltaCol;

                    if (self::isValidPosition($jumpRow, $jumpCol) &&
                        self::isDarkSquare($jumpRow, $jumpCol) &&
                        $state['board'][$jumpRow][$jumpCol] === self::EMPTY) {

                        $moves[] = [
                            'from' => ['row' => $row, 'col' => $col],
                            'to' => ['row' => $jumpRow, 'col' => $jumpCol],
                            'captures' => [['row' => $newRow, 'col' => $newCol]],
                            'type' => 'jump'
                        ];
                    }
                }
            }
        }

        return $moves;
    }

    /**
     * Get jump moves for a specific piece (for forced jump checking)
     */
    private static function getPieceJumps(array $state, int $row, int $col): array
    {
        $moves = [];
        $piece = $state['board'][$row][$col];
        $player = $state['currentPlayer'];

        if (!self::isPlayerPiece($piece, $player)) {
            return $moves;
        }

        $directions = self::getPieceDirections($piece);

        foreach ($directions as [$deltaRow, $deltaCol]) {
            $newRow = $row + $deltaRow;
            $newCol = $col + $deltaCol;

            if (self::isValidPosition($newRow, $newCol) && self::isDarkSquare($newRow, $newCol)) {
                // Jump move (enemy piece)
                if (self::isOpponentPiece($state['board'][$newRow][$newCol], $player)) {
                    $jumpRow = $newRow + $deltaRow;
                    $jumpCol = $newCol + $deltaCol;

                    if (self::isValidPosition($jumpRow, $jumpCol) &&
                        self::isDarkSquare($jumpRow, $jumpCol) &&
                        $state['board'][$jumpRow][$jumpCol] === self::EMPTY) {

                        $moves[] = [
                            'from' => ['row' => $row, 'col' => $col],
                            'to' => ['row' => $jumpRow, 'col' => $jumpCol],
                            'captures' => [['row' => $newRow, 'col' => $newCol]],
                            'type' => 'jump'
                        ];
                    }
                }
            }
        }

        return $moves;
    }

    /**
     * Get movement directions for a piece
     */
    private static function getPieceDirections(?string $piece): array
    {
        if (!$piece) {
            return [];
        }

        $isKing = self::isKing($piece);
        $isRed = self::isRedPiece($piece);

        if ($isKing) {
            // Kings can move in all diagonal directions
            return [[-1, -1], [-1, 1], [1, -1], [1, 1]];
        } elseif ($isRed) {
            // Red pieces move "up" the board (decreasing row)
            return [[-1, -1], [-1, 1]];
        } else {
            // Black pieces move "down" the board (increasing row)
            return [[1, -1], [1, 1]];
        }
    }

    /**
     * Make a move on the board
     */
    public static function makeMove(array $state, array $move): array
    {
        $newState = $state;
        $newState['board'] = $state['board']; // Copy the board
        $newState['moves'] = $state['moves'] + 1;
        $newState['lastMove'] = $move;
        $newState['moveHistory'][] = $move;

        // Move the piece
        $from = $move['from'];
        $to = $move['to'];
        $piece = $newState['board'][$from['row']][$from['col']];

        $newState['board'][$from['row']][$from['col']] = self::EMPTY;
        $newState['board'][$to['row']][$to['col']] = $piece;

        // Handle captures
        if (!empty($move['captures'])) {
            foreach ($move['captures'] as $capture) {
                $capturedPiece = $newState['board'][$capture['row']][$capture['col']];
                $newState['board'][$capture['row']][$capture['col']] = self::EMPTY;

                // Update captured pieces count
                if (self::isRedPiece($capturedPiece)) {
                    $newState['capturedPieces'][self::BLACK]++;
                } else {
                    $newState['capturedPieces'][self::RED]++;
                }
            }
        }

        // Check for king promotion
        $newState['board'][$to['row']][$to['col']] = self::checkForPromotion($piece, $to['row'], $to['col']);

        // Check for additional jumps (if this was a jump)
        if ($move['type'] === 'jump') {
            $additionalJumps = self::getPieceJumps($newState, $to['row'], $to['col']);

            if (!empty($additionalJumps)) {
                // There are more jumps available - don't switch players
                $newState['selectedSquare'] = ['row' => $to['row'], 'col' => $to['col']];
                $newState['jumpSequence'] = $additionalJumps;
                return $newState;
            }
        }

        // Switch players
        $newState['currentPlayer'] = $newState['currentPlayer'] === self::RED ? self::BLACK : self::RED;
        $newState['selectedSquare'] = null;
        $newState['jumpSequence'] = [];

        // Check for game over
        $newState = self::checkGameOver($newState);

        return $newState;
    }

    /**
     * Check if a piece should be promoted to king
     */
    private static function checkForPromotion(?string $piece, int $row, int $col): ?string
    {
        if (!$piece) {
            return $piece;
        }

        // Red pieces become kings when reaching row 0
        if ($piece === self::RED && $row === 0) {
            return self::RED_KING;
        }

        // Black pieces become kings when reaching row 7
        if ($piece === self::BLACK && $row === self::BOARD_SIZE - 1) {
            return self::BLACK_KING;
        }

        return $piece;
    }

    /**
     * Check if the game is over
     */
    private static function checkGameOver(array $state): array
    {
        $redPieces = 0;
        $blackPieces = 0;
        $redMoves = self::getValidMovesForPlayer($state, self::RED);
        $blackMoves = self::getValidMovesForPlayer($state, self::BLACK);

        // Count pieces and check for valid moves
        for ($row = 0; $row < self::BOARD_SIZE; $row++) {
            for ($col = 0; $col < self::BOARD_SIZE; $col++) {
                if (self::isDarkSquare($row, $col)) {
                    $piece = $state['board'][$row][$col];
                    if ($piece === self::RED || $piece === self::RED_KING) {
                        $redPieces++;
                    } elseif ($piece === self::BLACK || $piece === self::BLACK_KING) {
                        $blackPieces++;
                    }
                }
            }
        }

        // Check win conditions
        if ($redPieces === 0 || empty($blackMoves)) {
            $state['gameOver'] = true;
            $state['winner'] = self::BLACK;
        } elseif ($blackPieces === 0 || empty($redMoves)) {
            $state['gameOver'] = true;
            $state['winner'] = self::RED;
        }

        return $state;
    }

    /**
     * Get valid moves for a specific player
     */
    private static function getValidMovesForPlayer(array $state, string $player): array
    {
        $originalPlayer = $state['currentPlayer'];
        $state['currentPlayer'] = $player;
        $moves = self::getValidMoves($state);
        $state['currentPlayer'] = $originalPlayer;

        return $moves;
    }

    /**
     * Check if a piece belongs to a player
     */
    public static function isPlayerPiece(?string $piece, string $player): bool
    {
        if (!$piece) {
            return false;
        }

        if ($player === self::RED) {
            return $piece === self::RED || $piece === self::RED_KING;
        } elseif ($player === self::BLACK) {
            return $piece === self::BLACK || $piece === self::BLACK_KING;
        }

        return false;
    }

    /**
     * Check if a piece is opponent's piece
     */
    private static function isOpponentPiece(?string $piece, string $player): bool
    {
        if (!$piece) {
            return false;
        }

        if ($player === self::RED) {
            return $piece === self::BLACK || $piece === self::BLACK_KING;
        } elseif ($player === self::BLACK) {
            return $piece === self::RED || $piece === self::RED_KING;
        }

        return false;
    }

    /**
     * Check if a piece is red
     */
    private static function isRedPiece(?string $piece): bool
    {
        return $piece === self::RED || $piece === self::RED_KING;
    }

    /**
     * Check if a piece is a king
     */
    public static function isKing(?string $piece): bool
    {
        return $piece === self::RED_KING || $piece === self::BLACK_KING;
    }

    /**
     * Get piece display name
     */
    public static function getPieceDisplayName(?string $piece): string
    {
        return match($piece) {
            self::RED => 'Red',
            self::BLACK => 'Black',
            self::RED_KING => 'Red King',
            self::BLACK_KING => 'Black King',
            default => 'Empty',
        };
    }

    /**
     * Get game statistics
     */
    public static function getStats(array $state): array
    {
        return [
            'moves' => $state['moves'],
            'currentPlayer' => $state['currentPlayer'],
            'capturedPieces' => $state['capturedPieces'],
            'redPieces' => self::countPlayerPieces($state, self::RED),
            'blackPieces' => self::countPlayerPieces($state, self::BLACK),
            'validMoves' => count(self::getValidMoves($state)),
        ];
    }

    /**
     * Count pieces for a player
     */
    private static function countPlayerPieces(array $state, string $player): int
    {
        $count = 0;

        for ($row = 0; $row < self::BOARD_SIZE; $row++) {
            for ($col = 0; $col < self::BOARD_SIZE; $col++) {
                if (self::isDarkSquare($row, $col) && self::isPlayerPiece($state['board'][$row][$col], $player)) {
                    $count++;
                }
            }
        }

        return $count;
    }
}

