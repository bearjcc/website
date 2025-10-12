<?php

namespace App\Livewire\Pages;

use App\Models\LorePage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.private')]
#[Title('Lore - Ursa Minor')]
class LoreIndex extends Component
{
    public function render()
    {
        $lorePages = LorePage::latest()->get();

        return view('livewire.pages.lore-index', [
            'lorePages' => $lorePages,
        ]);
    }
}
