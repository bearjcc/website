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
        if ($this->game->slug === 'letter-walker') {
            return view('games.letter-walker')->layout('layouts.blank');
        }

        $otherGames = Game::published()
            ->where('id', '!=', $this->game->id)
            ->orderBy('title')
            ->limit(5)
            ->get();

        return view('livewire.pages.game-show', [
            'motif' => $this->game->getMotifKey(),
            'otherGames' => $otherGames,
            'title' => $this->game->title . ' - Ursa Minor',
        ]);
    }
}
