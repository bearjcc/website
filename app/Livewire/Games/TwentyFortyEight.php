<?php

namespace App\Livewire\Games;

use App\Games\TwentyFortyEight\TwentyFortyEightGame;
use Livewire\Component;

class TwentyFortyEight extends Component
{
    public array $board = [];

    public int $score = 0;

    public bool $isWon = false;

    public bool $isOver = false;

    public int $bestScore = 0;

    public array $previousState = [];

    public function mount()
    {
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
    }

    public function move(string $direction)
    {
        if ($this->isOver) {
            return;
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

    public function render()
    {
        return view('livewire.games.twenty-forty-eight');
    }
}
