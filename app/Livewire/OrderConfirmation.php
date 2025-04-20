<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.order-confirmation', [
            'order' => $this->order,
        ]);
    }
}
