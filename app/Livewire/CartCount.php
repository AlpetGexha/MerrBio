<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartCount extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $count = 0;
        if (Auth::check()) {
            $count = Auth::user()->cartItems()->count();
        }

        return view('livewire.cart-count', [
            'count' => $count,
        ]);
    }
}
