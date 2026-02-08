<?php

declare(strict_types=1);

namespace App\Games\Chess;

use App\Games\Contracts\GameInterface;

/**
 * Chess Game implementation
 */
class ChessGame implements GameInterface
{
    public function id(): string
    {
        return 'chess';
    }

    public function name(): string
    {
        return 'Chess';
    }

    public function slug(): string
    {
        return 'chess';
    }

    public function description(): string
    {
        return 'The ultimate strategy game - checkmate your opponent!';
    }

    public function rules(): array
    {
        return [
            'Move pieces according to standard chess rules',
            'Capture opponent pieces by moving to their square',
            'Protect your king - checkmate ends the game',
            'Pawns promote when reaching the opposite end',
            'Special moves: castling and en passant available',
            'White moves first, then players alternate turns',
        ];
    }

    public function initialState(): array
    {
        return ChessEngine::initialState();
    }

    public function newGameState(): array
    {
        return ChessEngine::initialState();
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
        if (! isset($move['from']) || ! isset($move['to'])) {
            return $state;
        }

        $from = $move['from'];
        $to = $move['to'];

        // Validate positions
        if (! ChessEngine::isValidPosition($from['row'], $from['col']) ||
            ! ChessEngine::isValidPosition($to['row'], $to['col'])) {
            return $state;
        }

        // Check if it's the player's piece
        $piece = $state['board'][$from['row']][$from['col']] ?? null;
        if (! ChessEngine::isPlayerPiece($piece, $state['currentPlayer'])) {
            return $state;
        }

        // Check if move is valid
        $validMoves = ChessEngine::getValidMoves($state);

        foreach ($validMoves as $validMove) {
            if ($validMove['from']['row'] === $from['row'] &&
                $validMove['from']['col'] === $from['col'] &&
                $validMove['to']['row'] === $to['row'] &&
                $validMove['to']['col'] === $to['col']) {
                return ChessEngine::makeMoveInternal($state, $validMove);
            }
        }

        return $state;
    }

    public function validateMove(array $state, array $move): bool
    {
        if ($state['gameOver']) {
            return false;
        }

        // Validate move structure
        if (! isset($move['from']) || ! isset($move['to'])) {
            return false;
        }

        $from = $move['from'];
        $to = $move['to'];

        // Validate positions
        if (! ChessEngine::isValidPosition($from['row'], $from['col']) ||
            ! ChessEngine::isValidPosition($to['row'], $to['col'])) {
            return false;
        }

        // Check if it's the player's piece
        $piece = $state['board'][$from['row']][$from['col']] ?? null;
        if (! ChessEngine::isPlayerPiece($piece, $state['currentPlayer'])) {
            return false;
        }

        // Check if move is valid
        $validMoves = ChessEngine::getValidMoves($state);

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
        $stats = ChessEngine::getStats($state);
        $player = $state['currentPlayer'];

        // Chess scoring based on material advantage
        $pieceValues = [
            'pawn' => 1,
            'knight' => 3,
            'bishop' => 3,
            'rook' => 5,
            'queen' => 9,
            'king' => 0, // King has infinite value
        ];

        $playerMaterial = 0;
        $opponentMaterial = 0;

        $playerPieces = $player === ChessEngine::WHITE ? $stats['whitePieces'] : $stats['blackPieces'];
        $opponentPieces = $player === ChessEngine::WHITE ? $stats['blackPieces'] : $stats['whitePieces'];

        foreach ($pieceValues as $type => $value) {
            $playerMaterial += ($playerPieces[$type] ?? 0) * $value;
            $opponentMaterial += ($opponentPieces[$type] ?? 0) * $value;
        }

        // Add bonus for check
        $bonus = $state['inCheck'] ? 50 : 0;

        return max(0, $playerMaterial - $opponentMaterial + $bonus);
    }

    /**
     * Get valid moves for a square (for highlighting)
     */
    public function getValidMovesForSquare(array $state, int $row, int $col): array
    {
        $piece = $state['board'][$row][$col] ?? null;
        if (! ChessEngine::isPlayerPiece($piece, $state['currentPlayer'])) {
            return [];
        }

        return ChessEngine::getValidMoves($state);
    }

    /**
     * Check if a square has valid moves
     */
    public function hasValidMoves(array $state, int $row, int $col): bool
    {
        $moves = $this->getValidMovesForSquare($state, $row, $col);

        return ! empty($moves);
    }

    /**
     * Get piece at position
     */
    public function getPieceAt(array $state, int $row, int $col): ?string
    {
        if (! ChessEngine::isValidPosition($row, $col)) {
            return null;
        }

        return $state['board'][$row][$col] ?? null;
    }

    /**
     * Get piece display name
     */
    public function getPieceDisplayName(?string $piece): string
    {
        return match ($piece) {
            ChessEngine::WHITE_KING => 'White King',
            ChessEngine::WHITE_QUEEN => 'White Queen',
            ChessEngine::WHITE_ROOK => 'White Rook',
            ChessEngine::WHITE_BISHOP => 'White Bishop',
            ChessEngine::WHITE_KNIGHT => 'White Knight',
            ChessEngine::WHITE_PAWN => 'White Pawn',
            ChessEngine::BLACK_KING => 'Black King',
            ChessEngine::BLACK_QUEEN => 'Black Queen',
            ChessEngine::BLACK_ROOK => 'Black Rook',
            ChessEngine::BLACK_BISHOP => 'Black Bishop',
            ChessEngine::BLACK_KNIGHT => 'Black Knight',
            ChessEngine::BLACK_PAWN => 'Black Pawn',
            default => 'Empty',
        };
    }

    /**
     * Get game statistics
     */
    public function getGameStats(array $state): array
    {
        return ChessEngine::getStats($state);
    }

    /**
     * Get algebraic notation for a move
     */
    public function getMoveNotation(array $move): string
    {
        $from = ChessEngine::positionToAlgebraic($move['from']['row'], $move['from']['col']);
        $to = ChessEngine::positionToAlgebraic($move['to']['row'], $move['to']['col']);

        $piece = ChessEngine::getPieceType($move['piece']);
        $capture = $move['captured'] ? 'x' : '';
        $special = '';

        if ($move['special'] === 'castling_kingside') {
            return 'O-O';
        } elseif ($move['special'] === 'castling_queenside') {
            return 'O-O-O';
        }

        return $piece.$capture.$to;
    }
}
