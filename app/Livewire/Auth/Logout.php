<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.logout');
    }
}
