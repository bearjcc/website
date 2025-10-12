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
        // Get featured blocks (up to 3 games)
        $featured = FeatureBlock::query()
            ->where('kind', 'game')
            ->orderBy('order')
            ->limit(3)
            ->get()
            ->map(fn ($block) => $block->getReference())
            ->filter();

        // If we have featured games, use them; otherwise, get latest published games
        $games = $featured->isNotEmpty()
            ? $featured
            : Game::published()->latest()->limit(3)->get();

        // Get first published game for hero CTA
        $firstPublishedGameSlug = Game::published()
            ->orderBy('id')
            ->value('slug');

        // Get latest posts for studio section
        $posts = Post::published()->latest()->limit(2)->get();

        return view('livewire.pages.home', [
            'games' => $games,
            'firstPublishedGameSlug' => $firstPublishedGameSlug,
            'posts' => $posts,
        ]);
    }
}
