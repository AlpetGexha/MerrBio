<?php

namespace App\Livewire;

use Livewire\Component;

class BecomeFarmer extends Component
{
    public function becomeFarmer()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        auth()->user()->update([
            'role' => 'farmer',
        ]);

        session()->flash('success', 'You are now a farmer! You can access your farmer dashboard.');

        // return redirect()->route('filament.farmer.pages.dashboard');
    }

    public function render()
    {
        return view('livewire.become-farmer');
    }
}
