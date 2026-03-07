<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Ursa Minor - Small games. Big craft.')]
class Home extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $games = Game::published()->latest()->get();
        $firstPublishedGameSlug = Game::published()->orderBy('id')->value('slug');

        return view('livewire.pages.home', [
            'games' => $games,
            'firstPublishedGameSlug' => $firstPublishedGameSlug,
        ]);
    }
}
