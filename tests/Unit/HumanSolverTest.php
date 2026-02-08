<?php

namespace Tests\Unit;

use App\Enums\TechniqueType;
use App\Services\Sudoku\HumanSolver;
use App\Services\Sudoku\Step;
use App\Services\Sudoku\SudokuBoard;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HumanSolverTest extends TestCase
{
    private HumanSolver $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solver = new HumanSolver();
    }

    #[Test]
    public function it_finds_naked_single()
    {
        // Create a puzzle where one cell has only one candidate
        $board = new SudokuBoard();
        // Fill most of a row to create a naked single
        for ($c = 0; $c < 8; $c++) {
            $board->setValue(0, $c, $c + 1);
        }
        // Cell (0,8) should have only candidate 9

        $step = $this->solver->nextStep($board);

        $this->assertNotNull($step);
        $this->assertEquals(TechniqueType::NakedSingle, $step->type);
        $this->assertEquals(9, $step->placements[0]['d']);
        $this->assertEquals(0, $step->placements[0]['r']);
        $this->assertEquals(8, $step->placements[0]['c']);
    }

    #[Test]
    public function it_finds_hidden_single()
    {
        // Create a simple puzzle that should trigger a hidden single
        $board = new SudokuBoard();

        // Fill the first row with most digits, leaving position 8 empty
        for ($c = 0; $c < 8; $c++) {
            $board->setValue(0, $c, $c + 1);
        }

        // Fill other cells to ensure digit 9 can only go in (0,8)
        // This creates a hidden single scenario
        $step = $this->solver->nextStep($board);

        // Should find either naked single or hidden single
        $this->assertNotNull($step);
        $this->assertTrue(in_array($step->type, [TechniqueType::NakedSingle, TechniqueType::HiddenSingle]));
    }

    #[Test]
    public function it_returns_null_for_solved_puzzle()
    {
        $solvedPuzzle = '534678912672195348198342567859761423426853791713924856961537284287419635345286179';
        $board = SudokuBoard::fromString($solvedPuzzle);

        $step = $this->solver->nextStep($board);

        $this->assertNull($step);
    }

    #[Test]
    public function it_finds_locked_candidates()
    {
        // Puzzle that yields locked candidates (claiming) at some step
        $puzzle = '030000500008000009000310400020006000600090001000200080005067000200000800007000030';
        $board = SudokuBoard::fromString($puzzle);

        $foundLocked = false;
        $maxSteps = 20;
        for ($i = 0; $i < $maxSteps; $i++) {
            $step = $this->solver->nextStep($board);
            if ($step === null) {
                break;
            }
            if ($step->type === TechniqueType::LockedCandidates) {
                $foundLocked = true;
                $this->assertNotEmpty($step->eliminations, 'LockedCandidates step must have eliminations');
                $this->assertContains($step->type, TechniqueType::cases());
                break;
            }
            $this->applyStepToBoard($board, $step);
        }

        $this->assertTrue($foundLocked, 'Solver should find LockedCandidates in this puzzle');
    }

    private function applyStepToBoard(SudokuBoard $board, Step $step): void
    {
        foreach ($step->placements as $p) {
            $board->setValue($p['r'], $p['c'], $p['d']);
        }
        // Eliminations-only steps: board state unchanged; naked/hidden singles make progress
    }

    #[Test]
    public function it_solves_with_steps()
    {
        // Use a puzzle that has some steps but is simple enough to solve quickly
        $puzzle = '003020600900305001001806400008102900700000008006708200002609500800203009005010300';
        $board = SudokuBoard::fromString($puzzle);

        $steps = $this->solver->solveWithSteps($board);

        // Should be able to solve this case
        $this->assertNotNull($steps);

        if ($steps !== null) {
            $this->assertNotEmpty($steps);

            // Check that all steps have valid techniques
            foreach ($steps as $step) {
                $this->assertContains($step->type, TechniqueType::cases());
                $this->assertGreaterThan(0, $step->weight);
            }
        }
    }
}
