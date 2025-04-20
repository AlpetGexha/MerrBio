<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductListing extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public array $filters = [
        'category' => null,
        'min_price' => null,
        'max_price' => null,
        'in_stock' => false,
        'sort_by' => 'created_at',
        'sort_direction' => 'desc',
    ];

    public function updatedFilters($value, $key)
    {
        if ($key === 'in_stock') {
            $this->filters['in_stock'] = (bool) $value;
        }
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("JSON_EXTRACT(name, '$') LIKE ?", ["%{$this->search}%"])
                        ->orWhereRaw("JSON_EXTRACT(description, '$') LIKE ?", ["%{$this->search}%"])
                        ->orWhereRaw("JSON_EXTRACT(keywords, '$') LIKE ?", ["%{$this->search}%"])
                        ->orWhere('sku', 'LIKE', "%{$this->search}%");
                });
            })
            ->when($this->filters['category'], function ($query) {
                $query->where('category_id', $this->filters['category']);
            })
            ->when($this->filters['min_price'], function ($query) {
                $query->where('price', '>=', $this->filters['min_price']);
            })
            ->when($this->filters['max_price'], function ($query) {
                $query->where('price', '<=', $this->filters['max_price']);
            })
            ->when($this->filters['in_stock'], function ($query) {
                $query->where('is_in_stock', true);
            })
            ->orderBy($this->filters['sort_by'], $this->filters['sort_direction'])
            ->paginate(12);
    }

    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    public function addToCart($productId)
    {
        if (! Auth::check()) {
            session()->flash('notification', [
                'type' => 'error',
                'message' => 'Please login to add items to cart',
            ]);

            return;
        }

        $product = Product::findOrFail($productId);
        $user = Auth::user();

        // Check if product is already in cart
        $cartItem = $user->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            $user->cartItems()->create([
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        // Dispatch event to update cart count
        $this->dispatch('cartUpdated')->to(CartCount::class);

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Product added to cart successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.product-listing');
    }
}
