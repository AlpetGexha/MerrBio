<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Order;
use App\Models\ShippingAddress;
use Filament\Notifications\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Checkout extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|max:255')]
    public string $phone = '';

    #[Rule('required|string|max:255')]
    public string $address_line1 = '';

    #[Rule('nullable|string|max:255')]
    public ?string $address_line2 = null;

    #[Rule('required|string|max:255')]
    public string $city = '';

    #[Rule('required|string|max:255')]
    public string $state = '';

    #[Rule('required|string|max:255')]
    public string $postal_code = '';

    #[Rule('required|string|max:255')]
    public string $country = '';

    public bool $save_address = true;
    public ?int $selected_address_id = null;
    public bool $show_address_fields = true;
    public  $ordersCollection = [];

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

    public function mount()
    {
        if ($defaultAddress = Auth::user()->defaultShippingAddress) {
            $this->selected_address_id = $defaultAddress->id;
            $this->shipping_address = [
                'street' => $defaultAddress->address_line1,
                'city' => $defaultAddress->city,
                'state' => $defaultAddress->state,
                'zip_code' => $defaultAddress->postal_code,
                'country' => $defaultAddress->country,
            ];
            $this->show_address_fields = false;
        }
    }

    public function updatedSelectedAddressId($value)
    {
        if ($value) {
            $address = ShippingAddress::find($value);
            if ($address) {
                $this->shipping_address = [
                    'street' => $address->address_line1,
                    'city' => $address->city,
                    'state' => $address->state,
                    'zip_code' => $address->postal_code,
                    'country' => $address->country,
                ];
                $this->show_address_fields = false;
            }
        } else {
            $this->shipping_address = [
                'street' => '',
                'city' => '',
                'state' => '',
                'zip_code' => '',
                'country' => '',
            ];
            $this->show_address_fields = true;
        }
    }

    protected function saveShippingAddressIfRequired()
    {
        if ($this->save_address && $this->show_address_fields) {
            $address = Auth::user()->shippingAddresses()->create([
                'name' => $this->name,
                'phone' => $this->phone,
                'address_line1' => $this->shipping_address['street'],
                'address_line2' => $this->address_line2,
                'city' => $this->shipping_address['city'],
                'state' => $this->shipping_address['state'],
                'postal_code' => $this->shipping_address['zip_code'],
                'country' => $this->shipping_address['country'],
                'is_default' => true,
            ]);

            $address->setAsDefault();
        }
    }


    protected function seperateOrderByCompany()
    {
        // Group cart items by company
        $cartItemsByCompany = $this->cartItems->groupBy(function ($item) {
            return $item->product->company_id;
        });

        $orders = collect();

        // Create separate orders for each company
        foreach ($cartItemsByCompany as $companyId => $companyCartItems) {
            $order = Order::create([
                'uuid' => Str::uuid(),
                'user_id' => auth()->id(),
                'account_id' => 1,
                'company_id' => $companyId,
                'status' => 'pending',
                'total_amount' => $companyCartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                }),
                'currency' => 'USD',
                'shipping_address' => json_encode($this->shipping_address),
                'billing_address' => json_encode($this->same_as_shipping ? $this->shipping_address : $this->billing_address),
                'payment_method' => $this->payment_method,
                'payment_status' => 'pending',
            ]);

            foreach ($companyCartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'account_id' => 1,
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

            // on order collection save the fisrt order and the order count




            $orders->push($order);

            $data = [
                'order' => $orders->first(),
                'order_count' => $orders->count(),
            ];

            $this->ordersCollection = $data;
        }
    }


    public function checkout()
    {
        $user = Auth::user();

        $this->saveShippingAddressIfRequired();
        $this->seperateOrderByCompany();

        $user->cartItems()->delete();

        if ($this->ordersCollection['order_count'] === 1) {
            return $this->redirect(route('orders.show', $this->ordersCollection['order']));
        }

        return $this->redirect(route('orders.index'));
    }

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

    public function render()
    {
        return view('livewire.checkout', [
            'addresses' => Auth::user()->shippingAddresses()->latest()->get(),
        ]);
    }
}
