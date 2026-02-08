<?php

declare(strict_types=1);

namespace App\Services\Sudoku;

use App\Enums\Difficulty;

class DifficultyRating
{
    private const EASY_THRESHOLD = 30;

    private const MEDIUM_THRESHOLD = 80;

    /**
     * Rate puzzle difficulty
     * Returns array with total weight, band, and steps
     */
    public function rate(SudokuBoard $board): array
    {
        $humanSolver = new HumanSolver();
        $steps = $humanSolver->solveWithSteps(clone $board);

        if ($steps === null) {
            // Can't solve with human techniques alone
            return [
                'totalWeight' => 9999,
                'band' => Difficulty::Expert,
                'steps' => [],
                'solvable' => false,
            ];
        }

        $totalWeight = array_sum(array_map(fn ($step) => $step->weight, $steps));

        $band = match (true) {
            $totalWeight <= self::EASY_THRESHOLD => Difficulty::Easy,
            $totalWeight <= self::MEDIUM_THRESHOLD => Difficulty::Medium,
            default => Difficulty::Hard,
        };

        return [
            'totalWeight' => $totalWeight,
            'band' => $band,
            'steps' => $steps,
            'solvable' => true,
        ];
    }

    /**
     * Check if puzzle meets target difficulty
     */
    public function meetsTarget(SudokuBoard $board, Difficulty $target): bool
    {
        $rating = $this->rate($board);

        return $rating['band'] === $target && $rating['solvable'];
    }
}
