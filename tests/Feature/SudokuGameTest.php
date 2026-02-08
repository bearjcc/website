<?php

namespace Tests\Feature;

use App\Livewire\Games\Sudoku;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SudokuGameTest extends TestCase
{
    #[Test]
    public function it_generates_new_game()
    {
        Livewire::test(Sudoku::class)
            ->call('newGame')
            ->assertSet('gameComplete', false)
            ->assertSet('hintsUsed', 0);
    }

    #[Test]
    public function it_allows_setting_cell_value()
    {
        $component = Livewire::test(Sudoku::class)->call('newGame');
        $original = $component->get('originalPuzzle');
        for ($r = 0; $r < 9; $r++) {
            for ($c = 0; $c < 9; $c++) {
                if ($original[$r][$c] === 0) {
                    $component->call('placeNumberAt', $r, $c, 5);
                    $component->assertSet("board.{$r}.{$c}", 5);

                    return;
                }
            }
        }
        $this->fail('No empty cell found to place number');
    }

    #[Test]
    public function it_prevents_modifying_original_puzzle()
    {
        $component = Livewire::test(Sudoku::class);
        $component->call('newGame');

        $original = $component->get('originalPuzzle');
        for ($r = 0; $r < 9; $r++) {
            for ($c = 0; $c < 9; $c++) {
                if ($original[$r][$c] !== 0) {
                    $expected = $original[$r][$c];
                    $component->call('selectCell', $r, $c);
                    $component->call('placeNumber', 9);

                    // Board should be unchanged (given cells are read-only)
                    $this->assertSame($expected, $component->get('board')[$r][$c]);

                    return;
                }
            }
        }

        $this->fail('No original puzzle cells found');
    }

    #[Test]
    public function it_provides_hints()
    {
        Livewire::test(Sudoku::class)
            ->call('newGame')
            ->call('useHint')
            ->assertSet('hintsUsed', 1);
    }

    #[Test]
    public function it_loads_custom_puzzle()
    {
        $puzzle = '530070000600195000098000060800060003400803001700020006060000280000419005000080079';

        Livewire::test(Sudoku::class)
            ->set('customPuzzleInput', $puzzle)
            ->call('loadCustomPuzzle')
            ->assertSet('showCustomInput', false);
    }

    #[Test]
    public function it_validates_custom_puzzle_format()
    {
        Livewire::test(Sudoku::class)
            ->set('customPuzzleInput', '123') // Too short
            ->call('loadCustomPuzzle')
            ->assertDispatched('error');
    }

    #[Test]
    public function it_auto_solves_puzzle()
    {
        Livewire::test(Sudoku::class)
            ->call('newGame')
            ->call('autoSolve')
            ->assertSet('gameComplete', true);
    }

    #[Test]
    public function it_toggles_notes_mode()
    {
        Livewire::test(Sudoku::class)
            ->call('toggleNotesMode')
            ->assertSet('notesMode', true)
            ->call('toggleNotesMode')
            ->assertSet('notesMode', false);
    }

    #[Test]
    public function it_toggles_eliminations()
    {
        $component = Livewire::test(Sudoku::class)->call('newGame');
        $original = $component->get('originalPuzzle');
        $row = $col = null;
        for ($r = 0; $r < 9 && $row === null; $r++) {
            for ($c = 0; $c < 9; $c++) {
                if ($original[$r][$c] === 0) {
                    $row = $r;
                    $col = $c;
                    break;
                }
            }
        }
        $this->assertNotNull($row, 'No empty cell for elimination test');
        $component->call('toggleEliminationAt', $row, $col, 5)
            ->assertSet("eliminations.{$row}.{$col}", [5]);
        $component->call('toggleEliminationAt', $row, $col, 5)
            ->assertSet("eliminations.{$row}.{$col}", []);
        $component->call('toggleEliminationAt', $row, $col, 3)
            ->call('toggleEliminationAt', $row, $col, 7)
            ->assertSet("eliminations.{$row}.{$col}", [3, 7]);
    }

    #[Test]
    public function it_shows_remaining_numbers_when_eliminated()
    {
        $component = Livewire::test(Sudoku::class)->call('newGame');
        $original = $component->get('originalPuzzle');
        $row = $col = null;
        for ($r = 0; $r < 9; $r++) {
            for ($c = 0; $c < 9; $c++) {
                if ($original[$r][$c] === 0) {
                    $row = $r;
                    $col = $c;
                    break 2;
                }
            }
        }
        $this->assertNotNull($row, 'No empty cell for remaining-numbers test');
        $component->call('toggleEliminationAt', $row, $col, 1)
            ->call('toggleEliminationAt', $row, $col, 2)
            ->call('toggleEliminationAt', $row, $col, 3);
        $remaining = $component->instance()->getRemainingNumbers($row, $col);
        $this->assertEqualsCanonicalizing([4, 5, 6, 7, 8, 9], $remaining, 'Remaining numbers should exclude eliminated 1,2,3');
    }

    #[Test]
    public function it_prevents_elimination_of_placed_numbers()
    {
        $component = Livewire::test(Sudoku::class)->call('newGame');
        $original = $component->get('originalPuzzle');
        for ($r = 0; $r < 9; $r++) {
            for ($c = 0; $c < 9; $c++) {
                if ($original[$r][$c] === 0) {
                    $component->call('placeNumberAt', $r, $c, 5);
                    $component->call('toggleEliminationAt', $r, $c, 5);
                    $this->assertEmpty($component->get('eliminations')[$r][$c]);

                    return;
                }
            }
        }
        $this->fail('No empty cell found');
    }
}
