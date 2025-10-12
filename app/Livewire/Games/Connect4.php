<?php

namespace App\Livewire\Games;

use App\Games\Connect4\Connect4Engine;
use App\Games\Connect4\Connect4Game;
use Livewire\Component;

class Connect4 extends Component
{
    public array $state = [];
    public bool $showRules = false;

    public function mount()
    {
        $this->newGame();
    }

    public function newGame()
    {
        $game = new Connect4Game();
        $this->state = $game->newGameState();
        $this->showRules = false;
    }

    public function dropPiece(int $column)
    {
        if ($this->state['gameOver']) {
            return;
        }

        $game = new Connect4Game();
        $move = ['column' => $column];

        if ($game->validateMove($this->state, $move)) {
            $this->state = $game->applyMove($this->state, $move);
        }
    }

    public function toggleRules()
    {
        $this->showRules = !$this->showRules;
    }

    public function isWinningPiece(int $row, int $col): bool
    {
        if (!$this->state['gameOver'] || $this->state['winner'] === 'draw') {
            return false;
        }

        if (!isset($this->state['winningLine'])) {
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

    public function render()
    {
        return view('livewire.games.connect4');
    }
}

