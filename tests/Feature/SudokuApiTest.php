<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class SudokuApiTest extends TestCase
{
    public function test_generate_returns_puzzle_and_solution(): void
    {
        $response = $this->postJson(route('api.sudoku.generate'), [
            'difficulty' => 'easy',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'puzzle',
            'solution',
            'difficulty',
            'rating',
            'band',
            'seed',
        ]);
        $this->assertMatchesRegularExpression('/^[1-9.]{81}$/', $response->json('puzzle'));
        $this->assertMatchesRegularExpression('/^[1-9]{81}$/', $response->json('solution'));
        $this->assertSame('easy', $response->json('difficulty'));
    }
}
