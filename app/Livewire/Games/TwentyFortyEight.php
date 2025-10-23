<?php

namespace App\Livewire\Games;

use App\Games\TwentyFortyEight\TwentyFortyEightGame;
use App\Livewire\Concerns\InteractsWithGameState;
use App\Models\Game;
use Livewire\Attributes\Layout;
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
                    'isWon' => $state['isWon']
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
            0 => 'rgba(255, 255, 255, 0.05)',
            2 => '#eee4da',
            4 => '#ede0c8',
            8 => '#f2b179',
            16 => '#f59563',
            32 => '#f67c5f',
            64 => '#f65e3b',
            128 => '#edcf72',
            256 => '#edcc61',
            512 => '#edc850',
            1024 => '#edc53f',
            2048 => '#edc22e',
            default => '#3c3a32',
        };
    }

    public function getTileTextColor(int $value): string
    {
        return $value > 4 ? '#ffffff' : '#776e65';
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

    public function render()
    {
        return view('livewire.games.twenty-forty-eight');
    }
}
