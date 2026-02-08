<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\LorePage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.private')]
class LoreShow extends Component
{
    public LorePage $lorePage;

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.lore-show', [
            'title' => $this->lorePage->title.' - Lore',
        ]);
    }
}
