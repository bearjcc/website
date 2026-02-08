<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('About - Ursa Minor')]
class About extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.about');
    }
}
