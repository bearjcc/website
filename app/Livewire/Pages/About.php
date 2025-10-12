<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('About - Ursa Minor')]
class About extends Component
{
    public function render()
    {
        return view('livewire.pages.about');
    }
}
