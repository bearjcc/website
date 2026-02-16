<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GamePlay extends Component
{
    public Game $game;

    /** Whether user has passed the entry screen and is on the play view (same URL). */
    public bool $started = false;

    /** Opponent/mode choice for games that support it (e.g. computer, friend, solo). */
    public string $mode = 'computer';

    /** Player symbol (X or O) for games that support it (e.g. Tic-Tac-Toe). */
    public string $playerSymbol = 'X';

    public function startGame(): void
    {
        $this->started = true;
    }

    /** Resolve Tic-Tac-Toe (and similar) gameMode from entry mode. */
    public function resolvedGameMode(): string
    {
        return match ($this->mode) {
            'friend' => 'pvp',
            'solo' => 'ai-easy',
            default => 'ai-medium',
        };
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if ($this->game->slug === 'letter-walker') {
            return view('games.letter-walker')->layout('layouts.blank');
        }

        return view('livewire.pages.game-play', [
            'title' => $this->game->title.' - Ursa Minor',
        ]);
    }
}
