<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\Chess\ChessEngine;
use App\Games\Chess\ChessGame;
use App\Livewire\Concerns\InteractsWithGameState;
use App\Models\Game;
use Livewire\Component;

class Chess extends Component
{
    use InteractsWithGameState;

    public Game $game;

    public array $board = [];

    public string $currentPlayer = 'white';

    public bool $gameOver = false;

    public ?string $winner = null;

    public int $moves = 0;

    public bool $inCheck = false;

    public array $castlingRights = [];

    public ?array $selectedSquare = null;

    public array $validMoves = [];

    public bool $showRules = false;

    public function mount()
    {
        $this->game = Game::where('slug', 'chess')->firstOrFail();
        $this->newGame();
    }

    public function newGame()
    {
        $game = new ChessGame();
        $state = $game->newGameState();
        $this->board = $state['board'];
        $this->currentPlayer = $state['currentPlayer'];
        $this->gameOver = $state['gameOver'];
        $this->winner = $state['winner'];
        $this->moves = $state['moves'];
        $this->inCheck = $state['inCheck'];
        $this->castlingRights = $state['castlingRights'];
        $this->selectedSquare = null;
        $this->validMoves = [];
        $this->showRules = false;
        $this->resetGame();
        $this->clearSavedState();
    }

    public function selectSquare(int $row, int $col)
    {
        // Don't allow moves if game is over
        if ($this->gameOver) {
            return;
        }

        $game = new ChessGame();
        $piece = $game->getPieceAt($this->getCurrentState(), $row, $col);

        // If clicking on own piece, select it
        if (ChessEngine::isPlayerPiece($piece, $this->currentPlayer)) {
            $this->selectedSquare = ['row' => $row, 'col' => $col];
            $this->updateValidMoves();
        }
        // If clicking on a valid move destination, make the move
        elseif ($this->selectedSquare && $this->isValidMoveDestination($row, $col)) {
            $this->makeMove($this->selectedSquare, ['row' => $row, 'col' => $col]);
        }
        // Deselect if clicking elsewhere
        else {
            $this->selectedSquare = null;
            $this->validMoves = [];
        }
    }

    public function makeMove(array $from, array $to)
    {
        // Start timer on first move
        if (! $this->startTime) {
            $this->startTimer();
        }

        $game = new ChessGame();
        $move = [
            'from' => $from,
            'to' => $to,
        ];

        if ($game->validateMove($this->getCurrentState(), $move)) {
            $state = $game->applyMove($this->getCurrentState(), $move);
            $this->syncFromState($state);
            $this->incrementMoveCount();
            $this->saveState();

            // Check for game completion
            if ($this->gameOver) {
                $this->completeGame();

                // Dispatch completion event for celebration
                $this->dispatch('game-completed', [
                    'winner' => $this->winner === 'draw' ? 'draw' : 'player',
                    'score' => $game->getScore($state),
                    'moves' => $this->moves,
                    'time' => $this->getElapsedTime(),
                    'inCheck' => $this->inCheck,
                    'isWon' => $this->winner !== 'draw' && $this->winner !== null,
                ]);
            }
        }
    }

    public function updateValidMoves()
    {
        if (! $this->selectedSquare) {
            $this->validMoves = [];

            return;
        }

        $game = new ChessGame();
        $state = $this->getCurrentState();
        $allValidMoves = ChessEngine::getValidMoves($state);

        $this->validMoves = array_filter($allValidMoves, function ($move) {
            return $move['from']['row'] === $this->selectedSquare['row'] &&
                   $move['from']['col'] === $this->selectedSquare['col'];
        });
    }

    public function isValidMoveDestination(int $row, int $col): bool
    {
        foreach ($this->validMoves as $move) {
            if ($move['to']['row'] === $row && $move['to']['col'] === $col) {
                return true;
            }
        }

        return false;
    }

    public function getPieceAt(int $row, int $col): ?string
    {
        $game = new ChessGame();

        return $game->getPieceAt($this->getCurrentState(), $row, $col);
    }

    public function getPieceDisplayName(?string $piece): string
    {
        $game = new ChessGame();

        return $game->getPieceDisplayName($piece);
    }

    public function getMoveNotation(array $move): string
    {
        $game = new ChessGame();

        return $game->getMoveNotation($move);
    }

    public function toggleRules()
    {
        $this->showRules = ! $this->showRules;
    }

    protected function getCurrentState(): array
    {
        return [
            'board' => $this->board,
            'currentPlayer' => $this->currentPlayer,
            'gameOver' => $this->gameOver,
            'winner' => $this->winner,
            'moves' => $this->moves,
            'inCheck' => $this->inCheck,
            'castlingRights' => $this->castlingRights,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function syncFromState(array $state): void
    {
        $this->board = $state['board'];
        $this->currentPlayer = $state['currentPlayer'];
        $this->gameOver = $state['gameOver'];
        $this->winner = $state['winner'];
        $this->moves = $state['moves'];
        $this->inCheck = $state['inCheck'];
        $this->castlingRights = $state['castlingRights'];

        // Update trait properties
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    protected function getStateForStorage(): array
    {
        return [
            'board' => $this->board,
            'currentPlayer' => $this->currentPlayer,
            'gameOver' => $this->gameOver,
            'winner' => $this->winner,
            'moves' => $this->moves,
            'inCheck' => $this->inCheck,
            'castlingRights' => $this->castlingRights,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function restoreFromState(array $state): void
    {
        $this->board = $state['board'] ?? array_fill(0, 8, array_fill(0, 8, null));
        $this->currentPlayer = $state['currentPlayer'] ?? 'white';
        $this->gameOver = $state['gameOver'] ?? false;
        $this->winner = $state['winner'] ?? null;
        $this->moves = $state['moves'] ?? 0;
        $this->inCheck = $state['inCheck'] ?? false;
        $this->castlingRights = $state['castlingRights'] ?? [
            'white' => ['kingside' => true, 'queenside' => true],
            'black' => ['kingside' => true, 'queenside' => true],
        ];
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.chess');
    }
}
