<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\TwentyFortyEight\TwentyFortyEightGame;
use App\Livewire\Concerns\InteractsWithGameState;
use App\Models\Game;
use Livewire\Component;

class TwentyFortyEight extends Component
{
    use InteractsWithGameState;

    public Game $game;

    public array $board = [];

    public int $score = 0;

    public bool $isWon = false;

    public bool $isOver = false;

    public int $bestScore = 0;

    public array $previousState = [];

    public function mount()
    {
        $this->game = Game::where('slug', 'twenty-forty-eight')->firstOrFail();
        $this->newGame();
    }

    public function newGame()
    {
        $game = new TwentyFortyEightGame();
        $state = $game->newGameState();
        $this->board = $state['board'];
        $this->score = $state['score'];
        $this->isWon = $state['isWon'];
        $this->isOver = $state['isOver'];
        $this->previousState = [];
        $this->resetGame();
        $this->clearSavedState();
    }

    public function move(string $direction)
    {
        if ($this->isOver) {
            return;
        }

        // Start timer on first move
        if (! $this->startTime) {
            $this->startTimer();
        }

        // Save state for undo
        $this->previousState = [
            'board' => $this->board,
            'score' => $this->score,
            'isWon' => $this->isWon,
            'isOver' => $this->isOver,
        ];

        $game = new TwentyFortyEightGame();
        $state = $game->applyMove([
            'board' => $this->board,
            'score' => $this->score,
            'isWon' => $this->isWon,
            'isOver' => $this->isOver,
        ], ['dir' => $direction]);

        // Only update if board changed
        if ($state['board'] !== $this->board) {
            $this->board = $state['board'];
            $this->score = $state['score'];
            $this->isWon = $state['isWon'];
            $this->isOver = $state['isOver'];

            // Update best score
            if ($this->score > $this->bestScore) {
                $this->bestScore = $this->score;
            }

            $this->incrementMoveCount();
            $this->saveState();

            // Check for game completion
            if ($state['isWon'] || $state['isOver']) {
                $this->completeGame();

                // Dispatch completion event for celebration
                $this->dispatch('game-completed', [
                    'winner' => $state['isWon'] ? 'player' : 'game',
                    'score' => $this->score,
                    'moves' => $this->moveCount,
                    'time' => $this->getElapsedTime(),
                    'maxTile' => max($this->board),
                    'isWon' => $state['isWon'],
                ]);
            }
        }
    }

    public function undo()
    {
        if (! empty($this->previousState)) {
            $this->board = $this->previousState['board'];
            $this->score = $this->previousState['score'];
            $this->isWon = $this->previousState['isWon'];
            $this->isOver = $this->previousState['isOver'];
            $this->previousState = [];
            $this->saveState();
        }
    }

    public function getTileColor(int $value): string
    {
        return match ($value) {
            0 => 'transparent',
            2 => 'hsl(45 90% 95%)',      // Light cream
            4 => 'hsl(45 85% 90%)',      // Slightly darker cream
            8 => 'hsl(35 85% 85%)',      // Light orange
            16 => 'hsl(30 85% 80%)',     // Orange
            32 => 'hsl(25 85% 75%)',     // Darker orange
            64 => 'hsl(20 85% 70%)',     // Red-orange
            128 => 'hsl(40 90% 75%)',    // Gold
            256 => 'hsl(40 85% 70%)',    // Darker gold
            512 => 'hsl(40 80% 65%)',    // Dark gold
            1024 => 'hsl(40 75% 60%)',   // Orange-gold
            2048 => 'hsl(40 70% 55%)',   // Dark orange-gold
            default => 'hsl(280 60% 40%)', // Purple for higher tiles
        };
    }

    public function getTileTextColor(int $value): string
    {
        return $value > 4 ? 'hsl(var(--space-900))' : 'hsl(220 15% 40%)';
    }

    protected function getCurrentState(): array
    {
        return [
            'board' => $this->board,
            'score' => $this->score,
            'isWon' => $this->isWon,
            'isOver' => $this->isOver,
            'bestScore' => $this->bestScore,
            'previousState' => $this->previousState,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function syncFromState(array $state): void
    {
        $this->board = $state['board'];
        $this->score = $state['score'];
        $this->isWon = $state['isWon'];
        $this->isOver = $state['isOver'];
        $this->bestScore = $state['bestScore'];
        $this->previousState = $state['previousState'] ?? [];
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    protected function getStateForStorage(): array
    {
        return [
            'board' => $this->board,
            'score' => $this->score,
            'isWon' => $this->isWon,
            'isOver' => $this->isOver,
            'bestScore' => $this->bestScore,
            'previousState' => $this->previousState,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function restoreFromState(array $state): void
    {
        $this->board = $state['board'] ?? [];
        $this->score = $state['score'] ?? 0;
        $this->isWon = $state['isWon'] ?? false;
        $this->isOver = $state['isOver'] ?? false;
        $this->bestScore = $state['bestScore'] ?? 0;
        $this->previousState = $state['previousState'] ?? [];
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.twenty-forty-eight');
    }
}
