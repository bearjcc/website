<?php

namespace App\Livewire\Games;

use App\Games\Connect4\Connect4Engine;
use App\Games\Connect4\Connect4Game;
use App\Livewire\Concerns\InteractsWithGameState;
use App\Models\Game;
use Livewire\Component;

class Connect4 extends Component
{
    use InteractsWithGameState;

    public Game $game;

    public array $state = [];

    public bool $showRules = false;

    public function mount()
    {
        $this->game = Game::where('slug', 'connect-4')->firstOrFail();
        $this->newGame();
    }

    public function newGame()
    {
        $game = new Connect4Game();
        $this->state = $game->newGameState();
        $this->showRules = false;
        $this->resetGame();
        $this->clearSavedState();
    }

    public function dropPiece(int $column)
    {
        if ($this->state['gameOver']) {
            return;
        }

        // Start timer on first move
        if (! $this->startTime) {
            $this->startTimer();
        }

        $game = new Connect4Game();
        $move = ['column' => $column];

        if ($game->validateMove($this->state, $move)) {
            $this->state = $game->applyMove($this->state, $move);
            $this->incrementMoveCount();
            $this->saveState();

            // Check for game completion
            if ($this->state['gameOver']) {
                $this->completeGame();

                // Dispatch completion event for celebration
                $this->dispatch('game-completed', [
                    'winner' => $this->state['winner'] === 'draw' ? 'draw' : 'player',
                    'score' => $this->state['score'] ?? [],
                    'moves' => $this->moveCount,
                    'time' => $this->getElapsedTime(),
                    'winningLine' => $this->state['winningLine'] ?? null,
                    'isWon' => $this->state['winner'] !== 'draw' && $this->state['winner'] !== null,
                ]);
            }
        }
    }

    public function toggleRules()
    {
        $this->showRules = ! $this->showRules;
    }

    public function isWinningPiece(int $row, int $col): bool
    {
        if (! $this->state['gameOver'] || $this->state['winner'] === 'draw') {
            return false;
        }

        if (! isset($this->state['winningLine'])) {
            return false;
        }

        foreach ($this->state['winningLine'] as $pos) {
            if ($pos['row'] === $row && $pos['col'] === $col) {
                return true;
            }
        }

        return false;
    }

    public function canDropInColumn(int $column): bool
    {
        return Connect4Engine::canDropInColumn($this->state, $column);
    }

    protected function getCurrentState(): array
    {
        return [
            'state' => $this->state,
            'showRules' => $this->showRules,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function syncFromState(array $state): void
    {
        $this->state = $state['state'];
        $this->showRules = $state['showRules'] ?? false;
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    protected function getStateForStorage(): array
    {
        return [
            'state' => $this->state,
            'showRules' => $this->showRules,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function restoreFromState(array $state): void
    {
        $this->state = $state['state'] ?? [];
        $this->showRules = $state['showRules'] ?? false;
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    public function render()
    {
        return view('livewire.games.connect4');
    }
}
