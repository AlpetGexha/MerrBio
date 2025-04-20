<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public function mount()
    {
        if (!Auth::check()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Please login to view your cart',
            ]);
        }
    }

    public function removeFromCart($cartItemId)
    {
        if (!Auth::check()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Please login to manage your cart',
            ]);
            return;
        }

        $cartItem = CartItem::findOrFail($cartItemId);

        // Verify the cart item belongs to the current user
        if ($cartItem->user_id !== Auth::id()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'You do not have permission to remove this item',
            ]);
            return;
        }

        $cartItem->delete();

        $this->dispatch('cartUpdated')->to(CartCount::class);

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Item removed from cart successfully!',
        ]);
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if (!Auth::check()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Please login to manage your cart',
            ]);
            return;
        }

        $cartItem = CartItem::findOrFail($cartItemId);

        // Verify the cart item belongs to the current user
        if ($cartItem->user_id !== Auth::id()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'You do not have permission to update this item',
            ]);
            return;
        }

        // Validate quantity
        if ($quantity < 1) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Quantity must be at least 1',
            ]);
            return;
        }

        $product = $cartItem->product;
        if (!$product->canBeAddedToCart()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'This product is out of stock',
            ]);
            return;
        }

        if ($quantity > $product->getMaxCartQuantity()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Maximum quantity reached for this product',
            ]);
            return;
        }

        $cartItem->update(['quantity' => $quantity]);

        $this->dispatch('cartUpdated')->to(CartCount::class);

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Cart updated successfully!',
        ]);
    }

    public function render()
    {
        if (!Auth::check()) {
            return view('livewire.cart', [
                'cartItems' => collect([]),
                'total' => 0,
                'isAuthenticated' => false,
            ]);
        }

        $cartItems = Auth::user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('livewire.cart', [
            'cartItems' => $cartItems,
            'total' => $total,
            'isAuthenticated' => true,
        ]);
    }
}
