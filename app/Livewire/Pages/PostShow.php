<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class PostShow extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        if ($post->status !== 'published') {
            abort(404);
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.post-show', [
            'title' => $this->post->title.' - Ursa Minor',
        ]);
    }
}
