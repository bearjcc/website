<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\Checkers\CheckersEngine;
use App\Games\Checkers\CheckersGame;
use App\Livewire\Concerns\InteractsWithGameState;
use App\Models\Game;
use Livewire\Component;

class Checkers extends Component
{
    use InteractsWithGameState;

    public Game $game;

    public array $board = [];

    public string $currentPlayer = 'red';

    public bool $gameOver = false;

    public ?string $winner = null;

    public int $moves = 0;

    public array $capturedPieces = ['red' => 0, 'black' => 0];

    public ?array $selectedSquare = null;

    public array $validMoves = [];

    public bool $showRules = false;

    public function mount(): void
    {
        $this->game = Game::where('slug', 'checkers')->firstOrFail();
        $this->newGame();
    }

    public function newGame()
    {
        $game = new CheckersGame();
        $this->board = $game->newGameState()['board'];
        $this->currentPlayer = $game->newGameState()['currentPlayer'];
        $this->gameOver = false;
        $this->winner = null;
        $this->moves = 0;
        $this->capturedPieces = ['red' => 0, 'black' => 0];
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

        // Check if clicking on a valid piece
        $piece = $this->board[$row][$col] ?? null;
        if (CheckersEngine::isPlayerPiece($piece, $this->currentPlayer)) {
            $this->selectedSquare = ['row' => $row, 'col' => $col];
            $this->updateValidMoves();
        }
        // Check if clicking on a valid move destination
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

        $game = new CheckersGame();
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
                    'capturedPieces' => $this->capturedPieces,
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

        $game = new CheckersGame();
        $state = $this->getCurrentState();
        $allValidMoves = CheckersEngine::getValidMoves($state);

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
        if (! CheckersEngine::isValidPosition($row, $col)) {
            return null;
        }

        return $this->board[$row][$col] ?? null;
    }

    public function isDarkSquare(int $row, int $col): bool
    {
        return CheckersEngine::isDarkSquare($row, $col);
    }

    public function isLightSquare(int $row, int $col): bool
    {
        return CheckersEngine::isLightSquare($row, $col);
    }

    public function isKing(?string $piece): bool
    {
        return CheckersEngine::isKing($piece);
    }

    public function getPieceDisplayName(?string $piece): string
    {
        return CheckersEngine::getPieceDisplayName($piece);
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
            'capturedPieces' => $this->capturedPieces,
            'selectedSquare' => $this->selectedSquare,
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
        $this->capturedPieces = $state['capturedPieces'];
        $this->selectedSquare = $state['selectedSquare'] ?? null;

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
            'capturedPieces' => $this->capturedPieces,
            'selectedSquare' => $this->selectedSquare,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function restoreFromState(array $state): void
    {
        $this->board = $state['board'] ?? array_fill(0, 8, array_fill(0, 8, null));
        $this->currentPlayer = $state['currentPlayer'] ?? 'red';
        $this->gameOver = $state['gameOver'] ?? false;
        $this->winner = $state['winner'] ?? null;
        $this->moves = $state['moves'] ?? 0;
        $this->capturedPieces = $state['capturedPieces'] ?? ['red' => 0, 'black' => 0];
        $this->selectedSquare = $state['selectedSquare'] ?? null;
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.checkers');
    }
}
