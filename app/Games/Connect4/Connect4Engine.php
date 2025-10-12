<?php

namespace App\Games\Connect4;

/**
 * Connect 4 game engine
 * Classic strategy game - get 4 in a row to win!
 */
class Connect4Engine
{
    public const ROWS = 6;

    public const COLS = 7;

    public const EMPTY = null;

    public const RED = 'red';

    public const YELLOW = 'yellow';

    /**
     * Initialize new game state
     */
    public static function initialState(): array
    {
        return [
            'board' => array_fill(0, self::ROWS, array_fill(0, self::COLS, self::EMPTY)),
            'currentPlayer' => self::RED,
            'gameOver' => false,
            'winner' => null,
            'winningLine' => null,
            'moves' => 0,
            'moveHistory' => [],
            'lastMove' => null,
            'gameTime' => 0,
            'mode' => 'pass_and_play', // pass_and_play, vs_ai
            'difficulty' => 'medium',
            'score' => [self::RED => 0, self::YELLOW => 0],
        ];
    }

    /**
     * Drop a piece in the specified column
     */
    public static function dropPiece(array $state, int $column): array
    {
        if ($state['gameOver'] || ! self::isValidColumn($column) || ! self::canDropInColumn($state, $column)) {
            return $state;
        }

        // Find the lowest available row
        $row = self::getLowestAvailableRow($state, $column);
        if ($row === -1) {
            return $state; // Column is full
        }

        $player = $state['currentPlayer'];

        // Place the piece
        $state['board'][$row][$column] = $player;
        $state['moves']++;

        // Record the move
        $move = ['player' => $player, 'row' => $row, 'column' => $column];
        $state['lastMove'] = $move;
        $state['moveHistory'][] = $move;

        // Check for win
        $winResult = self::checkWin($state, $row, $column, $player);
        if ($winResult['isWin']) {
            $state['gameOver'] = true;
            $state['winner'] = $player;
            $state['winningLine'] = $winResult['line'];
            $state['score'][$player]++;
        } elseif (self::isBoardFull($state)) {
            $state['gameOver'] = true;
            $state['winner'] = 'draw';
        } else {
            // Switch player
            $state['currentPlayer'] = $player === self::RED ? self::YELLOW : self::RED;
        }

        return $state;
    }

    /**
     * Check if a column is valid (0-6)
     */
    public static function isValidColumn(int $column): bool
    {
        return $column >= 0 && $column < self::COLS;
    }

    /**
     * Check if a piece can be dropped in the column
     */
    public static function canDropInColumn(array $state, int $column): bool
    {
        return self::isValidColumn($column) && $state['board'][0][$column] === self::EMPTY;
    }

    /**
     * Get the lowest available row in a column
     */
    public static function getLowestAvailableRow(array $state, int $column): int
    {
        for ($row = self::ROWS - 1; $row >= 0; $row--) {
            if ($state['board'][$row][$column] === self::EMPTY) {
                return $row;
            }
        }

        return -1; // Column is full
    }

    /**
     * Check if the board is full
     */
    public static function isBoardFull(array $state): bool
    {
        for ($col = 0; $col < self::COLS; $col++) {
            if ($state['board'][0][$col] === self::EMPTY) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check for win condition from the last move
     */
    public static function checkWin(array $state, int $row, int $col, string $player): array
    {
        $directions = [
            [0, 1],   // Horizontal
            [1, 0],   // Vertical
            [1, 1],   // Diagonal /
            [1, -1],   // Diagonal \
        ];

        foreach ($directions as $direction) {
            $line = self::checkDirection($state, $row, $col, $player, $direction[0], $direction[1]);
            if (count($line) >= 4) {
                return ['isWin' => true, 'line' => $line];
            }
        }

        return ['isWin' => false, 'line' => null];
    }

    /**
     * Check for 4 in a row in a specific direction
     */
    private static function checkDirection(array $state, int $row, int $col, string $player, int $deltaRow, int $deltaCol): array
    {
        $line = [['row' => $row, 'col' => $col]];

        // Check in positive direction
        $r = $row + $deltaRow;
        $c = $col + $deltaCol;
        while (self::isValidPosition($r, $c) && $state['board'][$r][$c] === $player) {
            $line[] = ['row' => $r, 'col' => $c];
            $r += $deltaRow;
            $c += $deltaCol;
        }

        // Check in negative direction
        $r = $row - $deltaRow;
        $c = $col - $deltaCol;
        while (self::isValidPosition($r, $c) && $state['board'][$r][$c] === $player) {
            array_unshift($line, ['row' => $r, 'col' => $c]);
            $r -= $deltaRow;
            $c -= $deltaCol;
        }

        return $line;
    }

    /**
     * Check if position is valid
     */
    private static function isValidPosition(int $row, int $col): bool
    {
        return $row >= 0 && $row < self::ROWS && $col >= 0 && $col < self::COLS;
    }

    /**
     * Get valid moves (available columns)
     */
    public static function getValidMoves(array $state): array
    {
        $validMoves = [];
        for ($col = 0; $col < self::COLS; $col++) {
            if (self::canDropInColumn($state, $col)) {
                $validMoves[] = $col;
            }
        }

        return $validMoves;
    }

    /**
     * Calculate game score
     */
    public static function getScore(array $state): array
    {
        $scores = $state['score'];

        // Add bonuses for quick wins
        if ($state['gameOver'] && $state['winner'] !== 'draw') {
            $winner = $state['winner'];
            $moveBonus = max(0, 42 - $state['moves']) * 10; // Faster wins get more points
            $scores[$winner] += $moveBonus;
        }

        return $scores;
    }

    /**
     * Get game statistics
     */
    public static function getStats(array $state): array
    {
        return [
            'moves' => $state['moves'],
            'gameTime' => $state['gameTime'],
            'currentPlayer' => $state['currentPlayer'],
            'mode' => $state['mode'],
            'difficulty' => $state['difficulty'],
            'score' => self::getScore($state),
            'piecesPlayed' => $state['moves'],
            'boardFull' => self::isBoardFull($state),
        ];
    }
}
