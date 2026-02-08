<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\Snake\SnakeEngine;
use App\Livewire\Concerns\InteractsWithGameState;
use App\Models\Game;
use Livewire\Component;

class Snake extends Component
{
    use InteractsWithGameState;

    public Game $game;

    public array $snake = [];

    public string $direction = 'right';

    public string $nextDirection = 'right';

    public array $food = [];

    public int $score = 0;

    public bool $gameOver = false;

    public bool $gameStarted = false;

    public int $speed = 150;

    public int $level = 1;

    public int $foodEaten = 0;

    public int $highScore = 0;

    public bool $paused = false;

    public function mount(): void
    {
        $this->game = Game::where('slug', 'snake')->firstOrFail();
        $this->newGame();
    }

    public function newGame()
    {
        $state = SnakeEngine::applyMove($this->getCurrentState(), [
            'action' => 'new_game',
        ]);
        $this->syncFromState($state);
        $this->resetGame();
    }

    public function changeDirection(string $newDirection)
    {
        $state = SnakeEngine::applyMove($this->getCurrentState(), [
            'action' => 'change_direction',
            'direction' => $newDirection,
        ]);
        $this->syncFromState($state);
        $this->incrementMoveCount();
        $this->saveState();
    }

    public function startGame()
    {
        $state = SnakeEngine::applyMove($this->getCurrentState(), [
            'action' => 'start_game',
        ]);
        $this->syncFromState($state);
        $this->startTimer();
        $this->saveState();
    }

    public function tick()
    {
        if ($this->gameOver || ! $this->gameStarted || $this->paused) {
            return;
        }

        $state = SnakeEngine::applyMove($this->getCurrentState(), [
            'action' => 'tick',
        ]);
        $this->syncFromState($state);
        $this->incrementMoveCount();
        $this->saveState();

        if ($state['gameOver']) {
            $this->completeGame();

            // Dispatch completion event for celebration
            $this->dispatch('game-completed', [
                'winner' => 'snake',
                'score' => $this->score,
                'level' => $this->level,
                'moves' => $this->moveCount,
                'time' => $this->getElapsedTime(),
                'length' => count($this->snake),
            ]);
        }
    }

    public function togglePause()
    {
        if ($this->gameStarted && ! $this->gameOver) {
            $action = $this->paused ? 'resume_game' : 'pause_game';
            $state = SnakeEngine::applyMove($this->getCurrentState(), [
                'action' => $action,
            ]);
            $this->syncFromState($state);
            $this->saveState();
        }
    }

    protected function getCurrentState(): array
    {
        return [
            'snake' => $this->snake,
            'direction' => $this->direction,
            'nextDirection' => $this->nextDirection,
            'food' => $this->food,
            'score' => $this->score,
            'gameOver' => $this->gameOver,
            'gameStarted' => $this->gameStarted,
            'speed' => $this->speed,
            'level' => $this->level,
            'foodEaten' => $this->foodEaten,
            'highScore' => $this->highScore,
            'gameTime' => $this->getElapsedTime(),
            'paused' => $this->paused,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function syncFromState(array $state): void
    {
        $this->snake = $state['snake'];
        $this->direction = $state['direction'];
        $this->nextDirection = $state['nextDirection'];
        $this->food = $state['food'];
        $this->score = $state['score'];
        $this->gameOver = $state['gameOver'];
        $this->gameStarted = $state['gameStarted'];
        $this->speed = $state['speed'];
        $this->level = $state['level'];
        $this->foodEaten = $state['foodEaten'];
        $this->highScore = $state['highScore'];
        $this->paused = $state['paused'];

        // Update trait properties
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    protected function getStateForStorage(): array
    {
        return [
            'snake' => $this->snake,
            'direction' => $this->direction,
            'nextDirection' => $this->nextDirection,
            'food' => $this->food,
            'score' => $this->score,
            'gameOver' => $this->gameOver,
            'gameStarted' => $this->gameStarted,
            'speed' => $this->speed,
            'level' => $this->level,
            'foodEaten' => $this->foodEaten,
            'highScore' => $this->highScore,
            'gameTime' => $this->getElapsedTime(),
            'paused' => $this->paused,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function restoreFromState(array $state): void
    {
        $this->snake = $state['snake'] ?? [];
        $this->direction = $state['direction'] ?? 'right';
        $this->nextDirection = $state['nextDirection'] ?? 'right';
        $this->food = $state['food'] ?? [];
        $this->score = $state['score'] ?? 0;
        $this->gameOver = $state['gameOver'] ?? false;
        $this->gameStarted = $state['gameStarted'] ?? false;
        $this->speed = $state['speed'] ?? 150;
        $this->level = $state['level'] ?? 1;
        $this->foodEaten = $state['foodEaten'] ?? 0;
        $this->highScore = $state['highScore'] ?? 0;
        $this->paused = $state['paused'] ?? false;
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.snake');
    }
}
