<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Common game state management behaviors for Livewire game components.
 *
 * Provides:
 * - localStorage persistence (save/resume)
 * - Common game lifecycle hooks
 * - Keyboard shortcut scaffolding
 * - State history for undo functionality
 */
trait InteractsWithGameState
{
    public bool $gameStarted = false;

    public bool $gameComplete = false;

    public int $moveCount = 0;

    public ?int $startTime = null;

    /**
     * Get the unique storage key for this game instance.
     */
    protected function getStorageKey(): string
    {
        return 'game_state_'.static::class.'_'.($this->game->id ?? 'default');
    }

    /**
     * Load saved state from localStorage (via wire:init or mount).
     */
    public function loadSavedState(): ?array
    {
        // In a real implementation, this would be handled client-side via Alpine
        // This is a server-side placeholder for the pattern
        return null;
    }

    /**
     * Save current state to localStorage.
     * Call this after each move.
     */
    public function saveState(): void
    {
        // Emit event to frontend to save state
        $this->dispatch('save-game-state', [
            'key' => $this->getStorageKey(),
            'state' => $this->getStateForStorage(),
        ]);
    }

    /**
     * Clear saved state (when starting new game).
     */
    public function clearSavedState(): void
    {
        $this->dispatch('clear-game-state', [
            'key' => $this->getStorageKey(),
        ]);
    }

    /**
     * Override this to define what state gets saved.
     */
    protected function getStateForStorage(): array
    {
        return [];
    }

    /**
     * Override this to restore from saved state.
     */
    protected function restoreFromState(array $state): void
    {
        // Implement in child components
    }

    /**
     * Track move count automatically.
     */
    protected function incrementMoveCount(): void
    {
        $this->moveCount++;
    }

    /**
     * Start game timer.
     */
    protected function startTimer(): void
    {
        $this->startTime = time();
        $this->gameStarted = true;
    }

    /**
     * Get elapsed time in seconds.
     */
    protected function getElapsedTime(): int
    {
        if (! $this->startTime) {
            return 0;
        }

        return time() - $this->startTime;
    }

    /**
     * Mark game as complete.
     */
    protected function completeGame(): void
    {
        $this->gameComplete = true;
        $this->clearSavedState();
    }

    /**
     * Reset game to initial state.
     * Override in child components to customize.
     */
    public function resetGame(): void
    {
        $this->gameStarted = false;
        $this->gameComplete = false;
        $this->moveCount = 0;
        $this->startTime = null;
        $this->clearSavedState();
    }
}

