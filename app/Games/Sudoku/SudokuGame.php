<?php

namespace App\Games\Sudoku;

use App\Games\Contracts\GameInterface;

class SudokuGame implements GameInterface
{
    public function id(): string
    {
        return 'sudoku';
    }

    public function name(): string
    {
        return 'Sudoku';
    }

    public function slug(): string
    {
        return 'sudoku';
    }

    public function description(): string
    {
        return 'Classic number puzzle - fill the 9×9 grid so each row, column, and 3×3 box contains digits 1-9 exactly once!';
    }

    public function newGameState(): array
    {
        return SudokuEngine::newGame();
    }

    public function isOver(array $state): bool
    {
        return SudokuEngine::isGameComplete($state);
    }

    public function applyMove(array $state, array $move): array
    {
        if (! SudokuEngine::validateMove($state, $move)) {
            return $state;
        }

        return SudokuEngine::applyMove($state, $move);
    }
}
