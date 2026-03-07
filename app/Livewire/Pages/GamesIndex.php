<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Component;

class GamesIndex extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $games = Game::published()
            ->orderBy('title')
            ->get();

        $paceGroups = [
            'quiet' => $games->filter(fn (Game $game): bool => $game->pace() === 'quiet')->values(),
            'steady' => $games->filter(fn (Game $game): bool => $game->pace() === 'steady')->values(),
            'lively' => $games->filter(fn (Game $game): bool => $game->pace() === 'lively')->values(),
        ];

        return view('livewire.pages.games-index', [
            'games' => $games,
            'paceGroups' => $paceGroups,
        ]);
    }
}
