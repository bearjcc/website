<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\FeatureBlock;
use App\Models\Game;
use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Admin - Feature Blocks')]
class AdminFeatures extends Component
{
    public string $kind = 'game';

    public string $ref_id = '';

    public int $order = 0;

    public function addFeature()
    {
        $this->validate([
            'kind' => 'required|in:game,post',
            'ref_id' => 'required|integer',
            'order' => 'required|integer',
        ]);

        FeatureBlock::create([
            'kind' => $this->kind,
            'ref_id' => $this->ref_id,
            'order' => $this->order,
        ]);

        $this->reset(['ref_id', 'order']);
        session()->flash('message', 'Feature block added!');
    }

    public function removeFeature($id)
    {
        FeatureBlock::find($id)->delete();
        session()->flash('message', 'Feature block removed!');
    }

    public function render()
    {
        $featureBlocks = FeatureBlock::orderBy('order')->get();
        $games = Game::published()->get();
        $posts = Post::published()->get();

        return view('livewire.pages.admin-features', [
            'featureBlocks' => $featureBlocks,
            'games' => $games,
            'posts' => $posts,
        ]);
    }
}
