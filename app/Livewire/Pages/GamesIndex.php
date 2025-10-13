<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Component;

class GamesIndex extends Component
{
    public function render()
    {
        $games = Game::published()
            ->orderBy('title')
            ->get();

        return view('livewire.pages.games-index', [
            'games' => $games,
        ]);
    }
}
