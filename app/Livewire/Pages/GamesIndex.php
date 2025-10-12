<?php

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Games - Ursa Minor')]
class GamesIndex extends Component
{
    public function render()
    {
        $games = Game::published()->orderBy('title')->get();

        return view('livewire.pages.games-index', [
            'games' => $games,
        ]);
    }
}
