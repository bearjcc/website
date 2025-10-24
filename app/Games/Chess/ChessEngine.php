<?php

namespace App\Games\Chess;

use Chess\Variant\Classical\Board as ChessBoard;
use Chess\Variant\Classical\PGN\Move as ChessMove;
use Chess\Variant\Classical\PGN\SanPlay;
use Chess\Play\SanPlay as SanPlayClass;

/**
 * Chess game engine using php-chess library
 * The ultimate strategy game - checkmate your opponent!
 */
class ChessEngine
{
    public const BOARD_SIZE = 8;

    public const EMPTY = null;

    public const WHITE = 'white';

    public const BLACK = 'black';

    /**
     * Initialize new game state
     */
    public static function initialState(): array
    {
        $board = new ChessBoard();
        $fen = $board->toFen();

        return [
            'board' => self::boardToArray($board),
            'currentPlayer' => self::WHITE,
            'gameOver' => false,
            'winner' => null,
            'moves' => 0,
            'moveHistory' => [],
            'lastMove' => null,
            'gameTime' => 0,
            'castlingRights' => [
                self::WHITE => ['kingside' => true, 'queenside' => true],
                self::BLACK => ['kingside' => true, 'queenside' => true],
            ],
            'enPassantTarget' => null,
            'halfmoveClock' => 0,
            'fullmoveNumber' => 1,
            'inCheck' => false,
            'fen' => $fen,
        ];
    }

    /**
     * Convert chess board to simple array format
     */
    private static function boardToArray(ChessBoard $board): array
    {
        $array = array_fill(0, self::BOARD_SIZE, array_fill(0, self::BOARD_SIZE, self::EMPTY));

        foreach ($board as $piece) {
            $square = $piece->getSquare();
            $row = self::BOARD_SIZE - 1 - intval(substr($square, 1));
            $col = ord(substr($square, 0, 1)) - ord('a');

            $array[$row][$col] = self::pieceToString($piece);
        }

        return $array;
    }

    /**
     * Convert piece object to string format
     */
    private static function pieceToString($piece): string
    {
        if (!$piece) {
            return self::EMPTY;
        }

        $color = $piece->getColor();
        $type = $piece->getIdentity();

        $colorPrefix = $color === 'W' ? 'white_' : 'black_';

        return match($type) {
            'K' => $colorPrefix . 'king',
            'Q' => $colorPrefix . 'queen',
            'R' => $colorPrefix . 'rook',
            'B' => $colorPrefix . 'bishop',
            'N' => $colorPrefix . 'knight',
            'P' => $colorPrefix . 'pawn',
            default => self::EMPTY,
        };
    }

    /**
     * Get all valid moves for the current player
     */
    public static function getValidMoves(array $state): array
    {
        $board = self::arrayToBoard($state['board']);
        $legalMoves = $board->getLegalMoves();

        $moves = [];
        foreach ($legalMoves as $move) {
            $fromSquare = $move->getFrom();
            $toSquare = $move->getTo();

            $fromRow = self::BOARD_SIZE - 1 - intval(substr($fromSquare, 1));
            $fromCol = ord(substr($fromSquare, 0, 1)) - ord('a');
            $toRow = self::BOARD_SIZE - 1 - intval(substr($toSquare, 1));
            $toCol = ord(substr($toSquare, 0, 1)) - ord('a');

            $moves[] = [
                'from' => ['row' => $fromRow, 'col' => $fromCol],
                'to' => ['row' => $toRow, 'col' => $toCol],
                'piece' => $state['board'][$fromRow][$fromCol],
                'captured' => $state['board'][$toRow][$toCol] !== self::EMPTY ? $state['board'][$toRow][$toCol] : null,
                'promotion' => false,
                'special' => self::getMoveSpecial($move),
                'notation' => $move->getSan(),
            ];
        }

        return $moves;
    }

    /**
     * Convert array board to ChessBoard object
     */
    private static function arrayToBoard(array $boardArray): ChessBoard
    {
        $board = new ChessBoard();

        // Clear existing pieces
        $board->rewind();
        while ($board->valid()) {
            $board->detach($board->current());
            $board->next();
        }

        // Add pieces from array
        for ($row = 0; $row < self::BOARD_SIZE; $row++) {
            for ($col = 0; $col < self::BOARD_SIZE; $col++) {
                $piece = $boardArray[$row][$col];
                if ($piece !== self::EMPTY) {
                    $square = self::arrayPosToSquare($row, $col);
                    $board->attach(self::stringToPiece($piece, $square));
                }
            }
        }

        return $board;
    }

    /**
     * Convert array position to chess square notation
     */
    private static function arrayPosToSquare(int $row, int $col): string
    {
        $file = chr(ord('a') + $col);
        $rank = self::BOARD_SIZE - $row;
        return $file . $rank;
    }

    /**
     * Convert piece string to piece object
     */
    private static function stringToPiece(string $pieceString, string $square)
    {
        $color = substr($pieceString, 0, 6) === 'white_' ? 'W' : 'B';
        $type = match(substr($pieceString, 6)) {
            'king' => 'K',
            'queen' => 'Q',
            'rook' => 'R',
            'bishop' => 'B',
            'knight' => 'N',
            'pawn' => 'P',
            default => null,
        };

        if (!$type) {
            return null;
        }

        $className = '\\Chess\\Variant\\Classical\\' . $type;

        return new $className($color, $square, new \Chess\Variant\Classical\PGN\Square());
    }

    /**
     * Get special move type
     */
    private static function getMoveSpecial($move): ?string
    {
        // For now, return null - can be enhanced to detect castling, en passant, etc.
        return null;
    }

    /**
     * Make a move on the board
     */
    public static function makeMove(array $state, array $move): array
    {
        $board = self::arrayToBoard($state['board']);

        try {
            // Create SAN move from our move format
            $fromSquare = self::arrayPosToSquare($move['from']['row'], $move['from']['col']);
            $toSquare = self::arrayPosToSquare($move['to']['row'], $move['to']['col']);

            $sanMove = $fromSquare . $toSquare;
            if ($move['promotion']) {
                $sanMove .= '=Q'; // Default to queen promotion
            }

            $board->play($sanMove);

            $newState = $state;
            $newState['board'] = self::boardToArray($board);
            $newState['moves'] = $state['moves'] + 1;
            $newState['lastMove'] = $move;
            $newState['moveHistory'][] = $move;

            // Switch players
            $newState['currentPlayer'] = $newState['currentPlayer'] === self::WHITE ? self::BLACK : self::WHITE;

            // Update FEN
            $newState['fen'] = $board->toFen();

            // Check for game over
            $newState = self::checkGameState($newState, $board);

            return $newState;

        } catch (\Exception $e) {
            // Invalid move
            return $state;
        }
    }

    /**
     * Check the overall game state
     */
    private static function checkGameState(array $state, ChessBoard $board): array
    {
        $state['inCheck'] = $board->isChecked($state['currentPlayer'] === self::WHITE ? 'W' : 'B');

        // Check for checkmate or stalemate
        if ($board->isMate()) {
            $state['gameOver'] = true;
            $state['winner'] = $state['currentPlayer'] === self::WHITE ? self::BLACK : self::WHITE;
        } elseif ($board->isStalemate()) {
            $state['gameOver'] = true;
            $state['winner'] = 'draw';
        }

        return $state;
    }

    /**
     * Check if a position is valid on the board
     */
    public static function isValidPosition(int $row, int $col): bool
    {
        return $row >= 0 && $row < self::BOARD_SIZE && $col >= 0 && $col < self::BOARD_SIZE;
    }

    /**
     * Check if a piece belongs to a player
     */
    public static function isPlayerPiece(?string $piece, string $player): bool
    {
        if (!$piece) {
            return false;
        }

        return str_starts_with($piece, $player . '_');
    }

    /**
     * Get piece color
     */
    private static function getPieceColor(string $piece): string
    {
        return str_starts_with($piece, 'white_') ? self::WHITE : self::BLACK;
    }

    /**
     * Get piece type
     */
    public static function getPieceType(string $piece): string
    {
        return match(substr($piece, 6)) {
            'king' => 'K',
            'queen' => 'Q',
            'rook' => 'R',
            'bishop' => 'B',
            'knight' => 'N',
            'pawn' => '',
            default => '?',
        };
    }

    /**
     * Convert position to algebraic notation
     */
    public static function positionToAlgebraic(int $row, int $col): string
    {
        return chr(97 + $col) . (8 - $row);
    }

    /**
     * Get game statistics
     */
    public static function getStats(array $state): array
    {
        return [
            'moves' => $state['moves'],
            'currentPlayer' => $state['currentPlayer'],
            'inCheck' => $state['inCheck'],
            'castlingRights' => $state['castlingRights'],
            'halfmoveClock' => $state['halfmoveClock'],
            'fullmoveNumber' => $state['fullmoveNumber'],
        ];
    }

    /**
     * Get piece at position
     */
    public static function getPieceAt(array $state, int $row, int $col): ?string
    {
        if (!self::isValidPosition($row, $col)) {
            return null;
        }

        return $state['board'][$row][$col] ?? null;
    }

    /**
     * Check if a piece is a king
     */
    public static function isKing(?string $piece): bool
    {
        return $piece && str_ends_with($piece, '_king');
    }

    /**
     * Get piece display name
     */
    public static function getPieceDisplayName(?string $piece): string
    {
        if (!$piece) {
            return 'Empty';
        }

        $color = str_starts_with($piece, 'white_') ? 'White' : 'Black';
        $type = match(substr($piece, 6)) {
            'king' => 'King',
            'queen' => 'Queen',
            'rook' => 'Rook',
            'bishop' => 'Bishop',
            'knight' => 'Knight',
            'pawn' => 'Pawn',
            default => 'Unknown',
        };

        return $color . ' ' . $type;
    }
}