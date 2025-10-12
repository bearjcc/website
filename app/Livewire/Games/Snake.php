<?php

namespace App\Livewire\Games;

use App\Games\Snake\SnakeEngine;
use Livewire\Component;

class Snake extends Component
{
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

    public function mount()
    {
        $this->newGame();
    }

    public function newGame()
    {
        $state = SnakeEngine::newGame();
        $this->syncFromState($state);
    }

    public function changeDirection(string $newDirection)
    {
        $state = $this->getCurrentState();
        $state = SnakeEngine::changeDirection($state, $newDirection);
        $this->syncFromState($state);
    }

    public function startGame()
    {
        $this->gameStarted = true;
    }

    public function tick()
    {
        if ($this->gameOver || ! $this->gameStarted || $this->paused) {
            return;
        }

        $state = $this->getCurrentState();
        $state = SnakeEngine::gameTick($state);
        $this->syncFromState($state);
    }

    public function togglePause()
    {
        if ($this->gameStarted && ! $this->gameOver) {
            $this->paused = ! $this->paused;
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
            'gameTime' => 0,
            'paused' => $this->paused,
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
    }

    public function render()
    {
        return view('livewire.games.snake');
    }
}
