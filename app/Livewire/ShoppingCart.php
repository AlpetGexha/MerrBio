<?php

namespace App\Livewire;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShoppingCart extends Component
{
    public array $quantity = [];

    #[Computed]
    public function cartItems()
    {
        return Auth::user()->cartItems()->with('product')->get();
    }

    #[Computed]
    public function total()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::find($cartItemId);
        if ($cartItem && $quantity > 0) {
            $cartItem->update(['quantity' => $quantity]);
        }
    }

    public function removeItem($cartItemId)
    {
        CartItem::destroy($cartItemId);
    }

    public function checkout()
    {
        return $this->redirect(route('checkout'));
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
