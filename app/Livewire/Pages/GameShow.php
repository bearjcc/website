<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GameShow extends Component
{
    public Game $game;

    public function render(): \Illuminate\Contracts\View\View
    {
        $otherGames = Game::published()
            ->where('id', '!=', $this->game->id)
            ->orderBy('title')
            ->limit(5)
            ->get();

        $calmerGames = Game::published()
            ->where('id', '!=', $this->game->id)
            ->get()
            ->filter(fn (Game $game): bool => $game->pace() === 'quiet')
            ->sortBy('title')
            ->take(3)
            ->values();

        return view($this->game->showView(), [
            'game' => $this->game,
            'motif' => $this->game->getMotifKey(),
            'otherGames' => $otherGames,
            'calmerGames' => $calmerGames,
        ])->layout($this->game->layoutView())
            ->title($this->game->title.' - Ursa Minor');
    }
}
