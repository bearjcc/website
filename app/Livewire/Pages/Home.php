<?php

namespace App\Livewire\Pages;

use App\Models\FeatureBlock;
use App\Models\Game;
use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Ursa Minor - Small games. Big craft.')]
class Home extends Component
{
    public function render()
    {
        // Get featured blocks (up to 3 games) - handle case where table doesn't exist
        $featured = collect();
        try {
            $featured = FeatureBlock::query()
                ->where('kind', 'game')
                ->orderBy('order')
                ->limit(3)
                ->get()
                ->map(fn ($block) => $block->getReference())
                ->filter();
        } catch (\Exception $e) {
            // Feature blocks table doesn't exist or is empty, use games directly
            $featured = collect();
        }

        // If we have featured games, use them; otherwise, get latest published games
        $games = $featured->isNotEmpty()
            ? $featured
            : $this->getGamesSafely();

        // Get first published game for hero CTA
        $firstPublishedGameSlug = $this->getFirstGameSlugSafely();

        // Get latest posts for studio section
        $posts = $this->getPostsSafely();

        return view('livewire.pages.home', [
            'games' => $games,
            'firstPublishedGameSlug' => $firstPublishedGameSlug,
            'posts' => $posts,
        ]);
    }

    private function getGamesSafely()
    {
        try {
            return Game::published()->latest()->limit(3)->get();
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

    private function getPostsSafely()
    {
        try {
            return Post::published()->latest()->limit(2)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
}
