<?php

namespace App\Livewire;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShoppingCart extends Component
{
    public array $quantity = [];
    public array $itemTotals = [];

    #[Computed]
    public function cartItems()
    {
        if (Auth::check()) {
            return Auth::user()->cartItems()->with('product')->get();
        }

        return [];
    }

    #[Computed]
    public function total()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    public function calculateItemTotal($quantity, $price)
    {
        return number_format($quantity * $price, 2);
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::find($cartItemId);
        if ($cartItem && $quantity > 0) {
            $cartItem->update(['quantity' => $quantity]);
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($cartItemId)
    {
        CartItem::destroy($cartItemId);
        $this->dispatch('cart-updated');
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
