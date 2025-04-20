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

    public $search = '';
    public $filters = [
        'category' => '',
        'price_range' => '',
        'in_stock' => false,
        'sort_by' => 'created_at',
        'sort_direction' => 'desc',
        'min_price' => '',
        'max_price' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => []],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ['%' . $this->search . '%'])
                      ->orWhereRaw("JSON_EXTRACT(description, '$.en') LIKE ?", ['%' . $this->search . '%'])
                      ->orWhere('sku', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->when($this->filters['category'], function ($query) {
                $query->where('category_id', $this->filters['category']);
            })
            ->when($this->filters['in_stock'], function ($query) {
                $query->where('is_in_stock', true);
            })
            ->when($this->filters['min_price'], function ($query) {
                $query->where('price', '>=', $this->filters['min_price']);
            })
            ->when($this->filters['max_price'], function ($query) {
                $query->where('price', '<=', $this->filters['max_price']);
            })
            ->when($this->filters['sort_by'], function ($query) {
                $query->orderBy($this->filters['sort_by'], $this->filters['sort_direction']);
            });

        return view('livewire.product-listing', [
            'products' => $query->paginate(12),
            'categories' => Category::all(),
        ]);
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
}
