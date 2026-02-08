<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Enums\Difficulty;
use App\Games\Sudoku\SudokuEngine;
use App\Services\Sudoku\HumanSolver;
use App\Services\Sudoku\SudokuBoard;
use App\Services\Sudoku\SudokuGenerator;
use App\Services\Sudoku\SudokuSolver;
use Livewire\Component;

/**
 * Interactive Sudoku Game Component
 *
 * This Livewire component provides a full-featured Sudoku gaming experience
 * with multiple difficulty levels, intelligent hints, custom puzzle loading,
 * and comprehensive validation.
 *
 * Features:
 * - Real-time puzzle solving with conflict detection
 * - Human-style hints with technique explanations
 * - Custom puzzle input with validation
 * - Notes mode for candidate tracking
 * - Adaptive hint limits based on difficulty
 * - Performance scoring system
 * - Responsive design for all devices
 *
 * The component integrates with advanced Sudoku services for reliable
 * puzzle generation, solving, and educational hinting.
 */
class Sudoku extends Component
{
    public array $board = [];

    public array $originalPuzzle = [];

    public array $solution = [];

    public array $notes = [];

    public array $eliminations = [];

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

    public ?array $hintStep = null;

    public bool $showCustomInput = false;

    public string $customPuzzleInput = '';

    public function mount(): void
    {
        $this->newGame();
    }

    public function newGame(?string $difficulty = null)
    {
        if ($difficulty) {
            $this->difficulty = $difficulty;
        }

        // Use new generator for better difficulty control
        try {
            $generator = new SudokuGenerator();
            $result = $generator->generate(Difficulty::from($this->difficulty));
            $board = $result['puzzle'];

            $state = [
                'board' => $board->toArray(),
                'originalPuzzle' => $board->toArray(),
                'solution' => $result['solution']->toArray(),
                'notes' => array_fill(0, 9, array_fill(0, 9, [])),
                'eliminations' => array_fill(0, 9, array_fill(0, 9, [])),
                'selectedCell' => null,
                'difficulty' => $this->difficulty,
                'hintsUsed' => 0,
                'maxHints' => $this->getMaxHintsForDifficulty($this->difficulty),
                'mistakes' => 0,
                'maxMistakes' => 3,
                'gameTime' => 0,
                'gameComplete' => false,
                'conflicts' => [],
                'notesMode' => false,
                'gameStarted' => false,
            ];

            $this->syncFromState($state);
        } catch (\Exception $e) {
            // Fallback to old engine if new one fails
            $state = SudokuEngine::newGame($this->difficulty);
            // Add eliminations to old engine state too
            $state['eliminations'] = array_fill(0, 9, array_fill(0, 9, []));
            $this->syncFromState($state);
        }

        $this->showDifficultySelector = false;
        $this->lastHint = null;
        $this->hintStep = null;
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
        if (! $this->canUseHint()) {
            return;
        }

        try {
            // Use new HumanSolver for better hints
            $board = new SudokuBoard($this->board);
            $humanSolver = new HumanSolver();
            $step = $humanSolver->nextStep($board);

            if ($step) {
                $this->lastHint = [$step->focusCells[0]['r'], $step->focusCells[0]['c']];
                $this->hintStep = [
                    'technique' => $step->type->value,
                    'technique_name' => $step->getTechniqueName(),
                    'explanation' => $step->explanation,
                    'placements' => $step->placements,
                    'eliminations' => $step->eliminations,
                ];

                // Apply the step
                foreach ($step->placements as $placement) {
                    $this->board[$placement['r']][$placement['c']] = $placement['d'];
                }

                $this->hintsUsed++;
                $this->validateBoard();

                $this->dispatch('hint-used');
            } else {
                $this->hintsUsed++; // Consume attempt so max-hints cap is enforced
                $this->dispatch('error', 'No more hints available!');
            }
        } catch (\Exception $e) {
            // Fallback to old hint system
            $hint = SudokuEngine::generateHint($this->getCurrentState());

            if (! $hint) {
                $this->dispatch('error', 'No more hints available!');

                return;
            }

            $this->lastHint = [$hint['row'], $hint['col']];
            $state = $this->getCurrentState();
            $state = SudokuEngine::useHint($state);
            $this->syncFromState($state);
            $this->dispatch('hint-used');
        }
    }

    public function autoSolve()
    {
        $state = SudokuEngine::autoSolve($this->getCurrentState());
        $this->syncFromState($state);
        $this->lastHint = null;
    }

    public function toggleCustomInput()
    {
        $this->showCustomInput = ! $this->showCustomInput;
        $this->customPuzzleInput = '';
    }

    public function loadCustomPuzzle()
    {
        try {
            // Parse input (expecting 81 characters: 0-9, where 0 is empty)
            $input = preg_replace('/[^0-9]/', '', $this->customPuzzleInput);

            if (strlen($input) !== 81) {
                $this->dispatch('error', 'Please enter exactly 81 digits (use 0 for empty cells)');

                return;
            }

            // Convert string to 9x9 array
            $puzzle = [];
            for ($row = 0; $row < 9; $row++) {
                $puzzle[$row] = [];
                for ($col = 0; $col < 9; $col++) {
                    $index = $row * 9 + $col;
                    $puzzle[$row][$col] = (int) $input[$index];
                }
            }

            // Use new SudokuBoard for validation
            $board = new SudokuBoard($puzzle);
            $validationReport = $board->getValidationReport();

            // Check for validation errors
            if (! $validationReport['isValid']) {
                $errorMessages = $validationReport['errors'];
                $this->dispatch('error', 'Puzzle validation failed: '.implode(', ', $errorMessages));

                return;
            }

            // Show warnings if any
            if (! empty($validationReport['warnings'])) {
                $warningMessages = $validationReport['warnings'];
                $this->dispatch('warning', 'Puzzle loaded with warnings: '.implode(', ', $warningMessages));
            }

            // Check if puzzle has a unique solution
            $solver = new SudokuSolver();
            if (! $solver->hasUniqueSolution($board)) {
                $this->dispatch('error', 'The puzzle does not have a unique solution - it may have multiple ways to be solved');

                return;
            }

            // Get the solution
            $solution = $solver->solve($board);
            if (! $solution) {
                $this->dispatch('error', 'The puzzle cannot be solved - it may be invalid');

                return;
            }

            // Calculate clue count
            $clueCount = 81 - substr_count($this->customPuzzleInput, '0');

            // Load puzzle with solution
            $state = [
                'board' => $puzzle,
                'originalPuzzle' => $puzzle,
                'solution' => $solution->toArray(),
                'notes' => array_fill(0, 9, array_fill(0, 9, [])),
                'selectedCell' => null,
                'difficulty' => 'custom',
                'hintsUsed' => 0,
                'maxHints' => max(1, min(6, intval($clueCount / 10))), // Adaptive hint limit based on clue count
                'mistakes' => 0,
                'maxMistakes' => 3,
                'gameTime' => 0,
                'gameComplete' => false,
                'conflicts' => [],
                'notesMode' => false,
                'gameStarted' => false,
            ];

            $this->syncFromState($state);

            $this->showCustomInput = false;
            $this->showDifficultySelector = false;
            $this->customPuzzleInput = '';

            $this->dispatch('success', 'Custom puzzle loaded successfully!');
        } catch (\Exception $e) {
            $this->dispatch('error', $e->getMessage());
        }
    }

    public function placeNumberAt(int $row, int $col, int $number)
    {
        // Don't allow changes to original puzzle cells
        if ($this->originalPuzzle[$row][$col] !== 0) {
            return;
        }

        $this->selectCell($row, $col);
        $this->placeNumber($number);
    }

    public function toggleNoteAt(int $row, int $col, int $number)
    {
        // Don't allow changes to original puzzle cells
        if ($this->originalPuzzle[$row][$col] !== 0) {
            return;
        }

        $state = $this->getCurrentState();
        $state = SudokuEngine::applyMove($state, [
            'action' => 'toggle_note',
            'row' => $row,
            'col' => $col,
            'number' => $number,
        ]);

        $this->syncFromState($state);
    }

    public function toggleEliminationAt(int $row, int $col, int $number)
    {
        // Don't allow changes to original puzzle cells
        if ($this->originalPuzzle[$row][$col] !== 0) {
            return;
        }

        // Don't allow eliminating if the number is already placed
        if ($this->board[$row][$col] === $number) {
            return;
        }

        // Toggle elimination
        if (! isset($this->eliminations[$row][$col])) {
            $this->eliminations[$row][$col] = [];
        }

        if (in_array($number, $this->eliminations[$row][$col])) {
            $this->eliminations[$row][$col] = array_filter(
                $this->eliminations[$row][$col],
                fn ($n) => $n !== $number
            );
        } else {
            $this->eliminations[$row][$col][] = $number;
        }

        // Re-index array to remove gaps
        $this->eliminations[$row][$col] = array_values($this->eliminations[$row][$col]);
    }

    protected function getCurrentState(): array
    {
        return [
            'board' => $this->board,
            'originalPuzzle' => $this->originalPuzzle,
            'solution' => $this->solution,
            'notes' => $this->notes,
            'eliminations' => $this->eliminations,
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
        $this->eliminations = $state['eliminations'] ?? array_fill(0, 9, array_fill(0, 9, []));
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

    public function canUseHint(): bool
    {
        return $this->hintsUsed < $this->maxHints && ! $this->gameComplete;
    }

    protected function validateBoard(): void
    {
        $this->conflicts = SudokuEngine::findConflicts($this->getCurrentState());
    }

    public function getMaxHintsForDifficulty(string $difficulty): int
    {
        return match ($difficulty) {
            'beginner' => 6,
            'easy' => 5,
            'medium' => 3,
            'hard' => 2,
            'expert' => 1,
            default => 3,
        };
    }

    public function getRemainingNumbers(int $row, int $col): array
    {
        if ($this->originalPuzzle[$row][$col] !== 0) {
            return [];
        }

        $allNumbers = range(1, 9);
        $eliminated = $this->eliminations[$row][$col] ?? [];

        $result = array_diff($allNumbers, $eliminated);

        // Ensure result is properly indexed (array_diff preserves keys which can cause issues)
        return array_values($result);
    }

    public function hasRemainingNumbers(int $row, int $col): bool
    {
        return count($this->getRemainingNumbers($row, $col)) <= 8 && count($this->getRemainingNumbers($row, $col)) > 0;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.sudoku');
    }
}
