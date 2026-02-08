<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\LorePage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.private')]
#[Title('Lore - Ursa Minor')]
class LoreIndex extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $lorePages = LorePage::latest()->get();

        return view('livewire.pages.lore-index', [
            'lorePages' => $lorePages,
        ]);
    }
}
