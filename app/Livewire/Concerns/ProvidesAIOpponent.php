<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

/**
 * Provides common AI opponent functionality for game components.
 *
 * Games using AI opponents can use this trait to standardize:
 * - AI difficulty levels
 * - Game mode management (PvP vs AI)
 * - Symbol selection for player
 * - AI turn handling
 */
trait ProvidesAIOpponent
{
    /** Game mode: 'pvp' or 'ai-{difficulty}' */
    public string $gameMode = 'pvp';

    /** Player's symbol when playing against AI */
    public string $playerSymbol = '';

    /** Available AI difficulty levels */
    protected array $aiDifficulties = ['easy', 'medium', 'impossible'];

    /**
     * Set the game mode and player symbol.
     */
    public function setGameMode(string $mode, ?string $symbol = null): void
    {
        $this->gameMode = $mode;

        if ($symbol !== null) {
            $this->playerSymbol = $symbol;
        }

        $this->newGame();
    }

    /**
     * Check if currently in AI mode.
     */
    protected function isAIMode(): bool
    {
        return str_starts_with($this->gameMode, 'ai-');
    }

    /**
     * Get the AI difficulty level from game mode.
     */
    protected function getAIDifficulty(): string
    {
        if (! $this->isAIMode()) {
            return 'easy';
        }

        return str_replace('ai-', '', $this->gameMode);
    }

    /**
     * Check if it's currently the AI's turn.
     */
    protected function isAITurn(): bool
    {
        if (! $this->isAIMode()) {
            return false;
        }

        // Override this in child class based on game state
        return $this->getCurrentPlayer() !== $this->playerSymbol;
    }

    /**
     * Get the current player.
     * Override this in child class.
     */
    protected function getCurrentPlayer(): string
    {
        return '';
    }

    /**
     * Trigger AI move after slight delay for better UX.
     * Override makeAiMove() in child class with actual AI logic.
     */
    protected function triggerAIMove(): void
    {
        if ($this->isAITurn()) {
            // In real implementation, you might dispatch a Livewire event
            // or call the AI move method directly
            $this->makeAiMove();
        }
    }

    /**
     * Make AI move.
     * Override this in child class with game-specific AI logic.
     */
    abstract protected function makeAiMove(): void;

    /**
     * Start new game.
     * Override this in child class.
     */
    abstract public function newGame(): void;
}
