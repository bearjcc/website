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

    public function render()
    {
        return view('livewire.pages.game-play', [
            'title' => $this->game->title.' - Ursa Minor',
        ]);
    }
}
