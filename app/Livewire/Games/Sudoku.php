<?php

namespace App\Livewire\Games;

use App\Games\Sudoku\SudokuEngine;
use Livewire\Component;

class Sudoku extends Component
{
    public array $board = [];

    public array $originalPuzzle = [];

    public array $solution = [];

    public array $notes = [];

    public ?array $selectedCell = null;

    public string $difficulty = 'medium';

    public int $hintsUsed = 0;

    public int $maxHints = 3;

    public int $mistakes = 0;

    public int $maxMistakes = 3;

    public int $gameTime = 0;

    public bool $gameComplete = false;

    public array $conflicts = [];

    public bool $notesMode = false;

    public bool $gameStarted = false;

    public bool $showDifficultySelector = true;

    public ?array $lastHint = null;

    public function mount()
    {
        $this->newGame();
    }

    public function newGame(?string $difficulty = null)
    {
        if ($difficulty) {
            $this->difficulty = $difficulty;
        }

        $state = SudokuEngine::newGame($this->difficulty);
        $this->syncFromState($state);
        $this->showDifficultySelector = false;
        $this->lastHint = null;
    }

    public function selectDifficulty(string $difficulty)
    {
        $this->difficulty = $difficulty;
        $this->newGame($difficulty);
    }

    public function selectCell(int $row, int $col)
    {
        $this->selectedCell = [$row, $col];
        $this->lastHint = null;
    }

    public function placeNumber(int $number)
    {
        if (! $this->selectedCell) {
            return;
        }

        [$row, $col] = $this->selectedCell;

        // Don't allow changes to original puzzle cells
        if ($this->originalPuzzle[$row][$col] !== 0) {
            return;
        }

        $state = $this->getCurrentState();

        if ($this->notesMode) {
            // Toggle note
            $state = SudokuEngine::applyMove($state, [
                'action' => 'toggle_note',
                'row' => $row,
                'col' => $col,
                'number' => $number,
            ]);
        } else {
            // Place number or clear if same number
            $numberToPlace = ($this->board[$row][$col] === $number) ? 0 : $number;
            $state = SudokuEngine::applyMove($state, [
                'action' => 'place_number',
                'row' => $row,
                'col' => $col,
                'number' => $numberToPlace,
            ]);
        }

        $this->syncFromState($state);
    }

    public function clearCell()
    {
        if (! $this->selectedCell) {
            return;
        }

        [$row, $col] = $this->selectedCell;

        if ($this->originalPuzzle[$row][$col] !== 0) {
            return;
        }

        $state = $this->getCurrentState();
        $state = SudokuEngine::applyMove($state, [
            'action' => 'clear_cell',
        ]);

        $this->syncFromState($state);
    }

    public function toggleNotesMode()
    {
        $this->notesMode = ! $this->notesMode;
    }

    public function useHint()
    {
        if (! SudokuEngine::canUseHint($this->getCurrentState())) {
            return;
        }

        $hint = SudokuEngine::generateHint($this->getCurrentState());

        if (! $hint) {
            return;
        }

        $this->lastHint = [$hint['row'], $hint['col']];

        $state = $this->getCurrentState();
        $state = SudokuEngine::useHint($state);
        $this->syncFromState($state);

        // Clear hint highlight after 2 seconds
        $this->dispatch('hint-used');
    }

    public function autoSolve()
    {
        $state = SudokuEngine::autoSolve($this->getCurrentState());
        $this->syncFromState($state);
        $this->lastHint = null;
    }

    protected function getCurrentState(): array
    {
        return [
            'board' => $this->board,
            'originalPuzzle' => $this->originalPuzzle,
            'solution' => $this->solution,
            'notes' => $this->notes,
            'selectedCell' => $this->selectedCell,
            'difficulty' => $this->difficulty,
            'hintsUsed' => $this->hintsUsed,
            'maxHints' => $this->maxHints,
            'mistakes' => $this->mistakes,
            'maxMistakes' => $this->maxMistakes,
            'gameTime' => $this->gameTime,
            'gameComplete' => $this->gameComplete,
            'conflicts' => $this->conflicts,
            'notesMode' => $this->notesMode,
            'gameStarted' => $this->gameStarted,
        ];
    }

    protected function syncFromState(array $state): void
    {
        $this->board = $state['board'];
        $this->originalPuzzle = $state['originalPuzzle'];
        $this->solution = $state['solution'];
        $this->notes = $state['notes'];
        $this->selectedCell = $state['selectedCell'];
        $this->difficulty = $state['difficulty'];
        $this->hintsUsed = $state['hintsUsed'];
        $this->maxHints = $state['maxHints'];
        $this->mistakes = $state['mistakes'];
        $this->maxMistakes = $state['maxMistakes'];
        $this->gameTime = $state['gameTime'];
        $this->gameComplete = $state['gameComplete'];
        $this->conflicts = $state['conflicts'];
        $this->notesMode = $state['notesMode'];
        $this->gameStarted = $state['gameStarted'];
    }

    public function isConflict(int $row, int $col): bool
    {
        foreach ($this->conflicts as $conflict) {
            if ($conflict[0] === $row && $conflict[1] === $col) {
                return true;
            }
        }

        return false;
    }

    public function isLastHint(int $row, int $col): bool
    {
        return $this->lastHint && $this->lastHint[0] === $row && $this->lastHint[1] === $col;
    }

    public function render()
    {
        return view('livewire.games.sudoku');
    }
}
