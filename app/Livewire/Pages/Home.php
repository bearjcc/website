<?php

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Ursa Minor - Small games. Big craft.')]
class Home extends Component
{
    public function render()
    {
        // Get all published games for carousel
        $games = $this->getGamesSafely();

        // Get first published game for hero CTA
        $firstPublishedGameSlug = $this->getFirstGameSlugSafely();

        return view('livewire.pages.home', [
            'games' => $games,
            'firstPublishedGameSlug' => $firstPublishedGameSlug,
        ]);
    }

    private function getGamesSafely()
    {
        try {
            return Game::published()->latest()->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getFirstGameSlugSafely()
    {
        try {
            return Game::published()->orderBy('id')->value('slug');
        } catch (\Exception $e) {
            return;
        }
    }
}
