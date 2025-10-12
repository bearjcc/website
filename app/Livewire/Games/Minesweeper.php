<?php

namespace App\Livewire\Games;

use App\Games\Minesweeper\MinesweeperEngine;
use App\Games\Minesweeper\MinesweeperGame;
use Livewire\Component;

class Minesweeper extends Component
{
    public array $board = [];
    public array $mines = [];
    public int $width = 9;
    public int $height = 9;
    public int $mineCount = 10;
    public string $difficulty = 'beginner';
    public bool $gameStarted = false;
    public bool $gameOver = false;
    public bool $gameWon = false;
    public bool $firstClick = true;
    public int $score = 0;
    public int $flagsUsed = 0;
    public int $squaresRevealed = 0;
    public bool $showDifficultySelector = true;

    public function mount()
    {
        $this->newGame();
    }

    public function newGame(?string $difficulty = null)
    {
        if ($difficulty) {
            $this->difficulty = $difficulty;
        }
        
        $state = MinesweeperEngine::newGame($this->difficulty);
        $this->syncFromState($state);
        $this->showDifficultySelector = false;
    }

    public function selectDifficulty(string $difficulty)
    {
        $this->difficulty = $difficulty;
        $this->newGame($difficulty);
    }

    public function revealCell(int $x, int $y)
    {
        if ($this->gameOver || $this->board[$y][$x]['revealed'] || $this->board[$y][$x]['flagged']) {
            return;
        }

        $state = $this->getCurrentState();
        
        // First click can't be a mine - regenerate if needed
        if ($this->firstClick && $this->board[$y][$x]['type'] === 'mine') {
            $state = MinesweeperEngine::newGame($this->difficulty);
            while ($state['board'][$y][$x]['type'] === 'mine') {
                $state = MinesweeperEngine::newGame($this->difficulty);
            }
        }

        $state = MinesweeperEngine::applyMove($state, [
            'action' => 'reveal_cell',
            'x' => $x,
            'y' => $y
        ]);

        $this->syncFromState($state);
    }

    public function flagCell(int $x, int $y)
    {
        if ($this->gameOver || $this->board[$y][$x]['revealed']) {
            return;
        }

        $state = $this->getCurrentState();
        $state = MinesweeperEngine::applyMove($state, [
            'action' => 'flag_cell',
            'x' => $x,
            'y' => $y
        ]);

        $this->syncFromState($state);
    }

    protected function getCurrentState(): array
    {
        return [
            'board' => $this->board,
            'mines' => $this->mines,
            'width' => $this->width,
            'height' => $this->height,
            'mineCount' => $this->mineCount,
            'difficulty' => $this->difficulty,
            'gameStarted' => $this->gameStarted,
            'gameOver' => $this->gameOver,
            'gameWon' => $this->gameWon,
            'firstClick' => $this->firstClick,
            'score' => $this->score,
            'flagsUsed' => $this->flagsUsed,
            'squaresRevealed' => $this->squaresRevealed,
            'startTime' => null,
            'endTime' => null,
            'gameTime' => 0,
            'bestTime' => null,
            'perfectGame' => true
        ];
    }

    protected function syncFromState(array $state): void
    {
        $this->board = $state['board'];
        $this->mines = $state['mines'];
        $this->width = $state['width'];
        $this->height = $state['height'];
        $this->mineCount = $state['mineCount'];
        $this->difficulty = $state['difficulty'];
        $this->gameStarted = $state['gameStarted'];
        $this->gameOver = $state['gameOver'];
        $this->gameWon = $state['gameWon'];
        $this->firstClick = $state['firstClick'];
        $this->score = $state['score'];
        $this->flagsUsed = $state['flagsUsed'];
        $this->squaresRevealed = $state['squaresRevealed'];
    }

    public function render()
    {
        return view('livewire.games.minesweeper');
    }
}
