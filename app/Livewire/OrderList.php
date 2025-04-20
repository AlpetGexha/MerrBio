<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public array $filters = [
        'status' => null,
        'sort_by' => 'created_at',
        'sort_direction' => 'desc',
    ];

    public function updatedFilters($value, $key)
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_number', 'LIKE', "%{$this->search}%")
                        ->orWhereHas('items.product', function ($q) {
                            $q->where('name', 'LIKE', "%{$this->search}%");
                        });
                });
            })
            ->when($this->filters['status'], function ($query) {
                $query->where('status', $this->filters['status']);
            })
            ->orderBy($this->filters['sort_by'], $this->filters['sort_direction'])
            ->paginate(10);

        return view('livewire.order-list', [
            'orders' => $orders,
        ]);
    }
}
