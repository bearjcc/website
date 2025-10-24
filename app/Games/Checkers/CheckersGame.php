<?php

namespace App\Games\Checkers;

use App\Games\Contracts\GameInterface;

/**
 * Checkers Game implementation
 */
class CheckersGame implements GameInterface
{
    public function id(): string
    {
        return 'checkers';
    }

    public function name(): string
    {
        return 'Checkers';
    }

    public function slug(): string
    {
        return 'checkers';
    }

    public function description(): string
    {
        return 'Classic strategy game - jump your way to victory!';
    }

    public function rules(): array
    {
        return [
            'Move pieces diagonally to empty dark squares',
            'Regular pieces move forward only (toward opponent)',
            'Jump over opponent pieces to capture them',
            'Multiple jumps allowed in a single turn',
            'Reach opponent\'s back row to crown your piece as a king',
            'Kings can move and jump in any diagonal direction',
            'Capture all opponent pieces or block them to win',
        ];
    }

    public function initialState(): array
    {
        return CheckersEngine::initialState();
    }

    public function newGameState(): array
    {
        return CheckersEngine::initialState();
    }

    public function isOver(array $state): bool
    {
        return $state['gameOver'] ?? false;
    }

    public function applyMove(array $state, array $move): array
    {
        if ($state['gameOver']) {
            return $state;
        }

        // Validate move structure
        if (!isset($move['from']) || !isset($move['to'])) {
            return $state;
        }

        $from = $move['from'];
        $to = $move['to'];

        // Validate positions
        if (!CheckersEngine::isValidPosition($from['row'], $from['col']) ||
            !CheckersEngine::isValidPosition($to['row'], $to['col'])) {
            return $state;
        }

        // Check if it's the player's piece
        $piece = $state['board'][$from['row']][$from['col']] ?? null;
        if (!CheckersEngine::isPlayerPiece($piece, $state['currentPlayer'])) {
            return $state;
        }

        // Get valid moves and check if this move is allowed
        $validMoves = CheckersEngine::getValidMoves($state);
        $isValidMove = false;

        foreach ($validMoves as $validMove) {
            if ($validMove['from']['row'] === $from['row'] &&
                $validMove['from']['col'] === $from['col'] &&
                $validMove['to']['row'] === $to['row'] &&
                $validMove['to']['col'] === $to['col']) {
                $isValidMove = true;
                break;
            }
        }

        if (!$isValidMove) {
            return $state;
        }

        return CheckersEngine::makeMove($state, $move);
    }

    public function validateMove(array $state, array $move): bool
    {
        if ($state['gameOver']) {
            return false;
        }

        // Validate move structure
        if (!isset($move['from']) || !isset($move['to'])) {
            return false;
        }

        $from = $move['from'];
        $to = $move['to'];

        // Validate positions
        if (!CheckersEngine::isValidPosition($from['row'], $from['col']) ||
            !CheckersEngine::isValidPosition($to['row'], $to['col'])) {
            return false;
        }

        // Check if it's the player's piece
        $piece = $state['board'][$from['row']][$from['col']] ?? null;
        if (!CheckersEngine::isPlayerPiece($piece, $state['currentPlayer'])) {
            return false;
        }

        // Check if move is valid
        $validMoves = CheckersEngine::getValidMoves($state);

        foreach ($validMoves as $validMove) {
            if ($validMove['from']['row'] === $from['row'] &&
                $validMove['from']['col'] === $from['col'] &&
                $validMove['to']['row'] === $to['row'] &&
                $validMove['to']['col'] === $to['col']) {
                return true;
            }
        }

        return false;
    }

    public function getScore(array $state): int
    {
        $stats = CheckersEngine::getStats($state);

        // Score based on pieces remaining and captured
        $player = $state['currentPlayer'];
        $score = $stats['capturedPieces'][$player] * 100; // 100 points per captured piece

        // Bonus for having more pieces than opponent
        $playerPieces = $player === CheckersEngine::RED ? $stats['redPieces'] : $stats['blackPieces'];
        $opponentPieces = $player === CheckersEngine::RED ? $stats['blackPieces'] : $stats['redPieces'];

        if ($playerPieces > $opponentPieces) {
            $score += ($playerPieces - $opponentPieces) * 50;
        }

        // Bonus for kings
        $kings = 0;
        for ($row = 0; $row < CheckersEngine::BOARD_SIZE; $row++) {
            for ($col = 0; $col < CheckersEngine::BOARD_SIZE; $col++) {
                $piece = $state['board'][$row][$col];
                if (CheckersEngine::isKing($piece) && CheckersEngine::isPlayerPiece($piece, $player)) {
                    $kings++;
                }
            }
        }

        $score += $kings * 200; // 200 points per king

        return $score;
    }

    /**
     * Get valid moves for a square (for highlighting)
     */
    public function getValidMovesForSquare(array $state, int $row, int $col): array
    {
        if (!CheckersEngine::isDarkSquare($row, $col)) {
            return [];
        }

        $piece = $state['board'][$row][$col] ?? null;
        if (!CheckersEngine::isPlayerPiece($piece, $state['currentPlayer'])) {
            return [];
        }

        return CheckersEngine::getValidMoves($state);
    }

    /**
     * Check if a square has valid moves
     */
    public function hasValidMoves(array $state, int $row, int $col): bool
    {
        $moves = $this->getValidMovesForSquare($state, $row, $col);
        return !empty($moves);
    }

    /**
     * Get piece at position
     */
    public function getPieceAt(array $state, int $row, int $col): ?string
    {
        if (!CheckersEngine::isValidPosition($row, $col)) {
            return null;
        }

        return $state['board'][$row][$col] ?? null;
    }

    /**
     * Check if a move would result in a capture
     */
    public function isCaptureMove(array $move): bool
    {
        return !empty($move['captures'] ?? []);
    }

    /**
     * Get game statistics
     */
    public function getGameStats(array $state): array
    {
        return CheckersEngine::getStats($state);
    }
}

