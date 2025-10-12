<?php

namespace App\Games\Snake;

use App\Games\Contracts\GameInterface;

class SnakeGame implements GameInterface
{
    public function id(): string { return 'snake'; }
    public function name(): string { return 'Snake'; }
    public function slug(): string { return 'snake'; }
    public function description(): string { return 'Classic Snake game - guide your snake to eat food and grow longer! Avoid hitting walls or yourself.'; }

    public function newGameState(): array
    {
        return SnakeEngine::newGame();
    }

    public function isOver(array $state): bool
    {
        return SnakeEngine::isGameOver($state);
    }

    public function applyMove(array $state, array $move): array
    {
        if (!SnakeEngine::validateMove($state, $move)) {
            return $state;
        }

        return SnakeEngine::applyMove($state, $move);
    }
}

