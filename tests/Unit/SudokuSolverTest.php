<?php

namespace Tests\Unit;

use App\Services\Sudoku\SudokuBoard;
use App\Services\Sudoku\SudokuSolver;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SudokuSolverTest extends TestCase
{
    private SudokuSolver $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solver = new SudokuSolver();
    }

    #[Test]
    public function it_solves_valid_puzzle()
    {
        // Use a simpler puzzle for faster testing
        $puzzle = '003020600900305001001806400008102900700000008006708200002609500800203009005010300';
        $board = SudokuBoard::fromString($puzzle);

        $solution = $this->solver->solve($board);

        $this->assertNotNull($solution);
        $this->assertTrue($solution->isSolved());
    }

    #[Test]
    public function it_detects_unique_solution()
    {
        // Use a simpler puzzle for faster testing
        $puzzle = '003020600900305001001806400008102900700000008006708200002609500800203009005010300';
        $board = SudokuBoard::fromString($puzzle);

        $hasUnique = $this->solver->hasUniqueSolution($board);

        $this->assertTrue($hasUnique);
    }

    #[Test]
    public function it_can_solve_empty_puzzle()
    {
        // Test that we can solve an empty puzzle (should return a valid solution)
        $puzzle = '000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        $board = SudokuBoard::fromString($puzzle);

        $solution = $this->solver->solve($board);

        // Should find a solution
        $this->assertNotNull($solution);
        $this->assertTrue($solution->isSolved());
        $this->assertTrue($solution->isValid());
    }

    #[Test]
    public function it_returns_null_for_unsolvable_puzzle()
    {
        // Create invalid puzzle with conflicts
        $board = new SudokuBoard();
        $board->setValue(0, 0, 1);
        $board->setValue(0, 1, 1); // Conflict

        $solution = $this->solver->solve($board);

        $this->assertNull($solution);
    }

    #[Test]
    public function it_counts_solutions()
    {
        // Use a simpler puzzle for faster testing
        $puzzle = '003020600900305001001806400008102900700000008006708200002609500800203009005010300';
        $board = SudokuBoard::fromString($puzzle);

        $count = $this->solver->countSolutions($board);

        $this->assertEquals(1, $count);
    }
}
