<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartIcon extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $cartCount = Auth::check()
            ? Auth::user()->cartItems()->sum('quantity')
            : 0;

        return view('livewire.cart-icon', [
            'cartCount' => $cartCount,
        ]);
    }
}
