<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\Connect4\Connect4Engine;
use App\Games\Connect4\Connect4Game;
use App\Livewire\Concerns\InteractsWithGameState;
use App\Models\Game;
use Livewire\Component;

class Connect4 extends Component
{
    use InteractsWithGameState;

    public Game $game;

    public ?string $initialMode = null;

    public string $entryMode = 'friend';

    public array $state = [];

    public bool $showRules = false;

    public function mount(): void
    {
        if (! isset($this->game->slug)) {
            $this->game = Game::where('slug', 'connect-4')->firstOrFail();
        }

        if (in_array($this->initialMode, ['computer', 'friend', 'solo'], true)) {
            $this->entryMode = $this->initialMode;
        }

        $this->newGame();
    }

    public function newGame(): void
    {
        $game = new Connect4Game();
        $this->state = $game->newGameState();
        $this->state['mode'] = $this->resolvedStateMode();
        $this->showRules = false;
        $this->resetGame();
        $this->clearSavedState();
    }

    public function dropPiece(int $column): void
    {
        if (($this->state['gameOver'] ?? false) || ! $this->canAcceptMove()) {
            return;
        }

        // Start timer on first move
        if (! $this->startTime) {
            $this->startTimer();
        }

        if (! $this->applyMove($column)) {
            return;
        }

        if ($this->shouldComputerMove()) {
            $this->makeComputerMove();
        }
    }

    public function toggleRules(): void
    {
        $this->showRules = ! $this->showRules;
    }

    public function isWinningPiece(int $row, int $col): bool
    {
        if (! $this->state['gameOver'] || $this->state['winner'] === 'draw') {
            return false;
        }

        if (! isset($this->state['winningLine'])) {
            return false;
        }

        foreach ($this->state['winningLine'] as $pos) {
            if ($pos['row'] === $row && $pos['col'] === $col) {
                return true;
            }
        }

        return false;
    }

    public function canDropInColumn(int $column): bool
    {
        return Connect4Engine::canDropInColumn($this->state, $column);
    }

    public function currentTurnLabel(): string
    {
        if (($this->state['gameOver'] ?? false) === true) {
            return ($this->state['winner'] ?? null) === 'draw'
                ? 'Draw'
                : ucfirst((string) $this->state['winner']).' wins';
        }

        if ($this->entryMode === 'computer') {
            return ($this->state['currentPlayer'] ?? Connect4Engine::RED) === Connect4Engine::RED
                ? 'Your turn'
                : 'Computer turn';
        }

        return ucfirst((string) ($this->state['currentPlayer'] ?? Connect4Engine::RED)).' to move';
    }

    public function modeLabel(): string
    {
        return match ($this->entryMode) {
            'computer' => 'vs Computer',
            'solo' => 'Practice',
            default => 'Pass and Play',
        };
    }

    protected function getCurrentState(): array
    {
        return [
            'state' => $this->state,
            'entryMode' => $this->entryMode,
            'showRules' => $this->showRules,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function syncFromState(array $state): void
    {
        $this->state = $state['state'];
        $this->entryMode = $state['entryMode'] ?? 'friend';
        $this->showRules = $state['showRules'] ?? false;
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    protected function getStateForStorage(): array
    {
        return [
            'state' => $this->state,
            'entryMode' => $this->entryMode,
            'showRules' => $this->showRules,
            'moveCount' => $this->moveCount,
            'startTime' => $this->startTime,
        ];
    }

    protected function restoreFromState(array $state): void
    {
        $this->state = $state['state'] ?? [];
        $this->entryMode = $state['entryMode'] ?? 'friend';
        $this->showRules = $state['showRules'] ?? false;
        $this->moveCount = $state['moveCount'] ?? 0;
        $this->startTime = $state['startTime'] ?? null;
    }

    protected function resolvedStateMode(): string
    {
        return $this->entryMode === 'computer' ? 'vs_ai' : 'pass_and_play';
    }

    protected function canAcceptMove(): bool
    {
        if ($this->entryMode !== 'computer') {
            return true;
        }

        return ($this->state['currentPlayer'] ?? Connect4Engine::RED) === Connect4Engine::RED;
    }

    protected function shouldComputerMove(): bool
    {
        return $this->entryMode === 'computer'
            && ! ($this->state['gameOver'] ?? false)
            && ($this->state['currentPlayer'] ?? Connect4Engine::RED) === Connect4Engine::YELLOW;
    }

    protected function applyMove(int $column): bool
    {
        $game = new Connect4Game();
        $move = ['column' => $column];

        if (! $game->validateMove($this->state, $move)) {
            return false;
        }

        $this->state = $game->applyMove($this->state, $move);
        $this->state['mode'] = $this->resolvedStateMode();
        $this->incrementMoveCount();
        $this->finishTurn();

        return true;
    }

    protected function makeComputerMove(): void
    {
        $column = Connect4Engine::chooseComputerMove($this->state, Connect4Engine::YELLOW);

        if ($column === null) {
            return;
        }

        $this->applyMove($column);
    }

    protected function finishTurn(): void
    {
        $this->saveState();

        if (! ($this->state['gameOver'] ?? false)) {
            return;
        }

        $this->completeGame();

        $winner = $this->state['winner'] ?? null;

        $this->dispatch('game-completed', [
            'winner' => $winner === 'draw' ? 'draw' : ($this->entryMode === 'computer' && $winner === Connect4Engine::YELLOW ? 'computer' : 'player'),
            'score' => $this->state['score'] ?? [],
            'moves' => $this->moveCount,
            'time' => $this->getElapsedTime(),
            'winningLine' => $this->state['winningLine'] ?? null,
            'isWon' => $winner !== 'draw' && $winner !== null,
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.connect4');
    }
}
