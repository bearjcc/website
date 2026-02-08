<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\Minesweeper\MinesweeperEngine;
use App\Livewire\Concerns\InteractsWithGameState;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Minesweeper extends Component
{
    use InteractsWithGameState;

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

    public bool $showRules = false;

    public function mount(): void
    {
        $this->newGame();
    }

    public function newGame(?string $difficulty = null): void
    {
        if ($difficulty) {
            $this->difficulty = $difficulty;
        }

        $state = MinesweeperEngine::newGame($this->difficulty);
        $this->syncFromState($state);
        $this->resetGame();
        $this->showDifficultySelector = false;
        $this->showRules = false;
    }

    public function selectDifficulty(string $difficulty): void
    {
        $this->difficulty = $difficulty;
        $this->newGame($difficulty);
    }

    public function resetGame(): void
    {
        $this->gameStarted = false;
        $this->gameComplete = false;
        $this->moveCount = 0;
        $this->startTime = null;
        $this->clearSavedState();
        $this->showDifficultySelector = true;
    }

    public function revealCell(int $x, int $y): void
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

        // Start timer on first move
        if (! $this->startTime) {
            $this->startTimer();
        }

        $state = MinesweeperEngine::applyMove($state, [
            'action' => 'reveal_cell',
            'x' => $x,
            'y' => $y,
        ]);

        $this->syncFromState($state);
        $this->incrementMoveCount();
        $this->saveState();

        if ($state['gameOver']) {
            $this->completeGame();

            // Dispatch completion event for celebration
            if ($state['gameWon']) {
                $this->dispatch('game-completed', [
                    'winner' => 'player',
                    'moves' => $this->moveCount,
                    'score' => $state['score'],
                    'time' => $this->getElapsedTime(),
                ]);
            } else {
                // Game lost - mine detonated
                $this->dispatch('game-completed', [
                    'winner' => 'mine',
                    'moves' => $this->moveCount,
                    'score' => $state['score'],
                    'time' => $this->getElapsedTime(),
                ]);
            }
        }
    }

    public function flagCell(int $x, int $y): void
    {
        if ($this->gameOver || $this->board[$y][$x]['revealed']) {
            return;
        }

        $state = $this->getCurrentState();
        $state = MinesweeperEngine::applyMove($state, [
            'action' => 'flag_cell',
            'x' => $x,
            'y' => $y,
        ]);

        $this->syncFromState($state);
        $this->incrementMoveCount();
        $this->saveState();
    }

    public function toggleRules(): void
    {
        $this->showRules = ! $this->showRules;
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
            'startTime' => $this->startTime,
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

    protected function getStateForStorage(): array
    {
        return [
            'board' => $this->board,
            'difficulty' => $this->difficulty,
            'gameStarted' => $this->gameStarted,
            'gameOver' => $this->gameOver,
            'gameWon' => $this->gameWon,
            'score' => $this->score,
            'flagsUsed' => $this->flagsUsed,
            'squaresRevealed' => $this->squaresRevealed,
            'moveCount' => $this->moveCount,
        ];
    }

    protected function restoreFromState(array $state): void
    {
        $this->board = $state['board'] ?? [];
        $this->difficulty = $state['difficulty'] ?? 'beginner';
        $this->gameStarted = $state['gameStarted'] ?? false;
        $this->gameOver = $state['gameOver'] ?? false;
        $this->gameWon = $state['gameWon'] ?? false;
        $this->score = $state['score'] ?? 0;
        $this->flagsUsed = $state['flagsUsed'] ?? 0;
        $this->squaresRevealed = $state['squaresRevealed'] ?? 0;
        $this->moveCount = $state['moveCount'] ?? 0;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.minesweeper');
    }
}
