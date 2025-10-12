<?php

namespace App\Livewire\Pages;

use App\Models\LorePage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.private')]
class LoreShow extends Component
{
    public LorePage $lorePage;

    public function render()
    {
        return view('livewire.pages.lore-show', [
            'title' => $this->lorePage->title.' - Lore',
        ]);
    }
}
