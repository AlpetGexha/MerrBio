<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Checkout extends Component
{
    public array $shipping_address = [
        'street' => '',
        'city' => '',
        'state' => '',
        'zip_code' => '',
        'country' => '',
    ];

    public array $billing_address = [
        'street' => '',
        'city' => '',
        'state' => '',
        'zip_code' => '',
        'country' => '',
    ];

    public string $payment_method = 'credit_card';

    public bool $same_as_shipping = true;

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

    public function placeOrder()
    {
        $user = Auth::user();

        // Find or create an account for the user
        $account = Account::firstOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'username' => $user->email,
                'type' => 'customer',
                'is_active' => true,
                'is_login' => true,
            ]
        );

        $order = Order::create([
            'uuid' => Str::uuid(),
            'user_id' => $user->id,
            'account_id' => $account->id,
            'status' => 'pending',
            'total_amount' => $this->total,
            'currency' => 'USD',
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->same_as_shipping ? $this->shipping_address : $this->billing_address,
            'payment_method' => $this->payment_method,
            'payment_status' => 'pending',
        ]);

        foreach ($this->cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'account_id' => $account->id,
                'item' => $item->product->name,
                'price' => $item->product->price,
                'qty' => $item->quantity,
                'total' => $item->product->price * $item->quantity,
                'discount' => 0,
                'vat' => 0,
                'returned' => 0,
                'returned_qty' => 0,
                'is_free' => false,
                'is_returned' => false,
                'options' => null,
            ]);
        }

        $user->cartItems()->delete();

        return $this->redirect(route('orders.show', $order));
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
