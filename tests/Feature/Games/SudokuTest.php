<?php

declare(strict_types=1);

namespace Tests\Feature\Games;

use App\Livewire\Games\Sudoku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SudokuTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders_successfully(): void
    {
        Livewire::test(Sudoku::class)
            ->assertStatus(200)
            ->assertSee('Sudoku');
    }

    public function test_game_starts_with_default_difficulty(): void
    {
        // Component auto-starts with medium difficulty by default
        Livewire::test(Sudoku::class)
            ->assertSet('difficulty', 'medium')
            ->assertSet('showDifficultySelector', false);
    }

    public function test_can_select_difficulty_and_start_game(): void
    {
        Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'easy')
            ->assertSet('difficulty', 'easy')
            ->assertSet('showDifficultySelector', false)
            ->assertSet('gameStarted', false); // Game starts on first move
    }

    public function test_can_select_cell(): void
    {
        Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'medium')
            ->call('selectCell', 0, 0)
            ->assertSet('selectedCell', [0, 0]);
    }

    public function test_can_place_number_in_empty_cell(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'beginner');

        // Find first empty cell
        $board = $component->get('board');
        $originalPuzzle = $component->get('originalPuzzle');
        
        $emptyRow = null;
        $emptyCol = null;
        
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($originalPuzzle[$row][$col] === 0) {
                    $emptyRow = $row;
                    $emptyCol = $col;
                    break 2;
                }
            }
        }

        if ($emptyRow !== null) {
            $component
                ->call('placeNumberAt', $emptyRow, $emptyCol, 5)
                ->assertSet("board.{$emptyRow}.{$emptyCol}", 5);
        }

        $this->assertTrue(true); // Pass if no empty cells found
    }

    public function test_cannot_change_original_puzzle_cells(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'beginner');

        // Find an original cell
        $originalPuzzle = $component->get('originalPuzzle');
        
        $originalRow = null;
        $originalCol = null;
        $originalValue = null;
        
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($originalPuzzle[$row][$col] !== 0) {
                    $originalRow = $row;
                    $originalCol = $col;
                    $originalValue = $originalPuzzle[$row][$col];
                    break 2;
                }
            }
        }

        if ($originalRow !== null) {
            $component
                ->call('placeNumberAt', $originalRow, $originalCol, 9)
                ->assertSet("board.{$originalRow}.{$originalCol}", $originalValue); // Should remain unchanged
        }

        $this->assertTrue(true);
    }

    public function test_hint_system_works(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'medium');

        $hintsBefore = $component->get('hintsUsed');
        
        $component->call('useHint')
            ->assertSet('hintsUsed', $hintsBefore + 1);

        // Check that lastHint is set
        $this->assertNotNull($component->get('lastHint'));
    }

    public function test_cannot_exceed_max_hints(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'expert'); // Only 1 hint

        $component->call('useHint'); // Use first hint
        $component->call('useHint'); // Try to use second (should fail)

        $component->assertSet('hintsUsed', 1); // Still 1
    }

    public function test_notes_mode_toggles(): void
    {
        Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'medium')
            ->assertSet('notesMode', false)
            ->call('toggleNotesMode')
            ->assertSet('notesMode', true)
            ->call('toggleNotesMode')
            ->assertSet('notesMode', false);
    }

    public function test_can_toggle_note_at_cell(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'beginner');

        // Find first empty cell
        $originalPuzzle = $component->get('originalPuzzle');
        
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($originalPuzzle[$row][$col] === 0) {
                    $component->call('toggleNoteAt', $row, $col, 5);
                    
                    $notes = $component->get('notes');
                    $this->assertContains(5, $notes[$row][$col]);
                    
                    // Toggle off
                    $component->call('toggleNoteAt', $row, $col, 5);
                    $notes = $component->get('notes');
                    $this->assertNotContains(5, $notes[$row][$col]);
                    
                    return; // Test one cell
                }
            }
        }

        $this->assertTrue(true);
    }

    public function test_can_clear_cell(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'beginner');

        // Find first empty cell and place number
        $originalPuzzle = $component->get('originalPuzzle');
        
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($originalPuzzle[$row][$col] === 0) {
                    $component->call('placeNumberAt', $row, $col, 7);
                    $component->call('selectCell', $row, $col);
                    $component->call('clearCell');
                    
                    $component->assertSet("board.{$row}.{$col}", 0);
                    return;
                }
            }
        }

        $this->assertTrue(true);
    }

    public function test_auto_solve_completes_puzzle(): void
    {
        Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'medium')
            ->call('autoSolve')
            ->assertSet('gameComplete', true);
    }

    public function test_custom_puzzle_input_shows_and_hides(): void
    {
        Livewire::test(Sudoku::class)
            ->assertSet('showCustomInput', false)
            ->call('toggleCustomInput')
            ->assertSet('showCustomInput', true)
            ->call('toggleCustomInput')
            ->assertSet('showCustomInput', false);
    }

    public function test_can_load_custom_puzzle(): void
    {
        // Valid Sudoku puzzle (from example)
        $puzzleString = '530070000600195000098000060800060003400803001700020006060000280000419005000080079';

        Livewire::test(Sudoku::class)
            ->set('customPuzzleInput', $puzzleString)
            ->call('loadCustomPuzzle')
            ->assertSet('difficulty', 'custom')
            ->assertSet('showCustomInput', false)
            ->assertSet('showDifficultySelector', false);
    }

    public function test_custom_puzzle_rejects_invalid_length(): void
    {
        Livewire::test(Sudoku::class)
            ->set('customPuzzleInput', '123') // Too short
            ->call('loadCustomPuzzle')
            ->assertDispatched('error');
    }

    public function test_custom_puzzle_handles_formatting(): void
    {
        // With spaces and newlines (should be stripped)
        $puzzleString = '530 070 000 
                        600 195 000 
                        098 000 060 
                        800 060 003 
                        400 803 001 
                        700 020 006 
                        060 000 280 
                        000 419 005 
                        000 080 079';

        Livewire::test(Sudoku::class)
            ->set('customPuzzleInput', $puzzleString)
            ->call('loadCustomPuzzle')
            ->assertSet('difficulty', 'custom');
    }

    public function test_new_game_resets_state(): void
    {
        Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'medium')
            ->call('useHint')
            ->assertSet('hintsUsed', 1)
            ->call('newGame')
            ->assertSet('hintsUsed', 0)
            ->assertSet('gameComplete', false)
            ->assertSet('mistakes', 0);
    }

    public function test_game_detects_conflicts(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'beginner');

        // Find two empty cells in same row
        $originalPuzzle = $component->get('originalPuzzle');
        $solution = $component->get('solution');
        
        for ($row = 0; $row < 9; $row++) {
            $emptyCells = [];
            for ($col = 0; $col < 9; $col++) {
                if ($originalPuzzle[$row][$col] === 0) {
                    $emptyCells[] = $col;
                }
            }
            
            if (count($emptyCells) >= 2) {
                // Place same number in two cells in same row
                $col1 = $emptyCells[0];
                $col2 = $emptyCells[1];
                $sameNumber = $solution[$row][$col1];
                
                $component->call('placeNumberAt', $row, $col1, $sameNumber);
                $component->call('placeNumberAt', $row, $col2, $sameNumber);
                
                $conflicts = $component->get('conflicts');
                $this->assertNotEmpty($conflicts);
                return;
            }
        }

        $this->assertTrue(true); // Pass if no suitable cells found
    }

    public function test_game_completes_when_solved(): void
    {
        $component = Livewire::test(Sudoku::class)
            ->call('selectDifficulty', 'beginner'); // Easiest difficulty

        // Fill in the solution
        $solution = $component->get('solution');
        $originalPuzzle = $component->get('originalPuzzle');

        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($originalPuzzle[$row][$col] === 0) {
                    $component->call('placeNumberAt', $row, $col, $solution[$row][$col]);
                }
            }
        }

        $component->assertSet('gameComplete', true);
    }
}
