<?php

namespace App\Livewire;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public function removeFromCart($cartItemId)
    {
        if (! Auth::check()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Please login to manage your cart',
            ]);

            return;
        }

        $cartItem = CartItem::findOrFail($cartItemId);

        // Check if the cart item belongs to the current user
        if ($cartItem->user_id !== Auth::id()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'You do not have permission to remove this item',
            ]);

            return;
        }

        $cartItem->delete();

        // Dispatch event to update cart count
        $this->dispatch('cartUpdated')->to(CartCount::class);

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Item removed from cart successfully!',
        ]);
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if (! Auth::check()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Please login to manage your cart',
            ]);

            return;
        }

        $cartItem = CartItem::findOrFail($cartItemId);

        // Check if the cart item belongs to the current user
        if ($cartItem->user_id !== Auth::id()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'You do not have permission to update this item',
            ]);

            return;
        }

        // Validate quantity
        if ($quantity < 1) {
            $quantity = 1;
        }

        $cartItem->update(['quantity' => $quantity]);

        // Dispatch event to update cart count
        $this->dispatch('cartUpdated')->to(CartCount::class);

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Quantity updated successfully!',
        ]);
    }

    public function render()
    {
        $cartItems = Auth::check()
            ? Auth::user()->cartItems()->with('product')->get()
            : collect();

        return view('livewire.cart', [
            'cartItems' => $cartItems,
        ]);
    }
}
