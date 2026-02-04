<?php

namespace Tests\Unit;

use App\Enums\Difficulty;
use App\Services\Sudoku\SudokuBoard;
use App\Services\Sudoku\SudokuGenerator;
use App\Services\Sudoku\SudokuSolver;
use Tests\TestCase;

class SudokuGeneratorTest extends TestCase
{
    private SudokuGenerator $generator;

    private SudokuSolver $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new SudokuGenerator(12345); // Fixed seed for testing
        $this->solver = new SudokuSolver();
    }

    /** @test */
    public function it_generates_valid_solution()
    {
        $result = $this->generator->generate(Difficulty::Easy);

        $this->assertArrayHasKey('solution', $result);
        $this->assertArrayHasKey('puzzle', $result);
        $this->assertInstanceOf(SudokuBoard::class, $result['solution']);
        $this->assertInstanceOf(SudokuBoard::class, $result['puzzle']);

        $this->assertTrue($result['solution']->isSolved());
        $this->assertTrue($result['puzzle']->isValid());
    }

    /** @test */
    public function it_generates_puzzle_with_unique_solution()
    {
        $result = $this->generator->generate(Difficulty::Easy); // Use Easy for faster testing

        $this->assertInstanceOf(SudokuBoard::class, $result['puzzle']);
        $this->assertInstanceOf(SudokuBoard::class, $result['solution']);
        $this->assertTrue($result['solution']->isSolved());
        $this->assertTrue($result['puzzle']->isValid());

        $hasUnique = $this->solver->hasUniqueSolution($result['puzzle']);

        $this->assertTrue($hasUnique);
    }

    /** @test */
    public function it_generates_puzzle_of_correct_difficulty()
    {
        $result = $this->generator->generate(Difficulty::Easy);

        // Allow adjacent difficulties (Easy or Medium)
        $this->assertTrue(
            in_array($result['rating']['band'], [Difficulty::Easy, Difficulty::Medium]),
            'Expected Easy or Medium difficulty, got '.$result['rating']['band']->value
        );
        $this->assertTrue($result['rating']['solvable']);
    }

    /** @test */
    public function it_generates_different_puzzles_with_different_seeds()
    {
        $generator1 = new SudokuGenerator(11111);
        $generator2 = new SudokuGenerator(22222);

        $result1 = $generator1->generate(Difficulty::Easy);
        $result2 = $generator2->generate(Difficulty::Easy);

        $this->assertNotEquals($result1['puzzle']->toString(), $result2['puzzle']->toString());
    }

    /** @test */
    public function it_generates_same_puzzle_with_same_seed()
    {
        $generator1 = new SudokuGenerator(99999);
        $generator2 = new SudokuGenerator(99999);

        $result1 = $generator1->generate(Difficulty::Easy); // Use Easy for faster testing
        $result2 = $generator2->generate(Difficulty::Easy);

        $this->assertEquals($result1['puzzle']->toString(), $result2['puzzle']->toString());
    }

    /** @test */
    public function it_includes_seed_in_result()
    {
        $seed = 54321;
        $generator = new SudokuGenerator($seed);

        $result = $generator->generate(Difficulty::Easy); // Use Easy for faster testing

        $this->assertEquals($seed, $result['seed']);
    }
}
