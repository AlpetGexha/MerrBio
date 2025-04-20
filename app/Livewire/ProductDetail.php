<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart()
    {
        if (! Auth::check()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Please login to add items to cart',
            ]);

            return;
        }

        if (! $this->product->canBeAddedToCart()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'This product is out of stock',
            ]);

            return;
        }

        $user = Auth::user();
        $cartItem = $user->cartItems()->where('product_id', $this->product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            $user->cartItems()->create([
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);
        }

        $this->dispatch('cartUpdated')->to(CartCount::class);

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Product added to cart successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
