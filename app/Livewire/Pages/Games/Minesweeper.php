<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Games;

use Livewire\Component;

class Minesweeper extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.games.minesweeper');
    }
}
