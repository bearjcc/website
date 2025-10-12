<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Blog - Ursa Minor')]
class BlogIndex extends Component
{
    public function render()
    {
        $posts = Post::published()->latest()->get();

        return view('livewire.pages.blog-index', [
            'posts' => $posts,
        ]);
    }
}
