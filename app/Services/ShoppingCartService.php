<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Livewire\Forms\CheckoutForm;
use App\Livewire\Forms\ShoppingCartForm;
use Illuminate\Support\Facades\DB;

class ShoppingCartService
{
    /**
     * Place an order using data from the checkout and cart forms
     *
     * @param CheckoutForm $checkoutForm
     * @param ShoppingCartForm $cartForm
     * @return Order
     */
    public function placeOrder(CheckoutForm $checkoutForm, ShoppingCartForm $cartForm): Order
    {
        return DB::transaction(function () use ($checkoutForm, $cartForm) {
            // Create the order
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'status' => 'pending',
                'total' => $cartForm->total,
                'subtotal' => $cartForm->subtotal,
                'tax' => $cartForm->tax,
                'shipping' => $cartForm->shipping,
                'discount' => $cartForm->discount,
                'shipping_address' => $checkoutForm->address_line1,
                'shipping_city' => $checkoutForm->city,
                'shipping_state' => $checkoutForm->state,
                'shipping_zipcode' => $checkoutForm->postal_code,
                'shipping_country' => $checkoutForm->country,
                'payment_method' => $checkoutForm->payment_method,
                'notes' => $checkoutForm->notes ?? '',
            ]);

            // Create order items
            foreach ($cartForm->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Update product inventory (optional)
                // Product::find($item['id'])->decrement('stock', $item['quantity']);
            }

            return $order;
        });
    }

    /**
     * Calculate shipping cost based on address and cart contents
     * This is a placeholder for real shipping calculation logic
     *
     * @param ShoppingCartForm $cartForm
     * @param string $country
     * @return float
     */
    public function calculateShipping(ShoppingCartForm $cartForm, string $country): float
    {
        // Simple shipping logic based on country
        // In a real app, you'd have more complex logic based on weight, distance, etc.
        $baseShipping = 10.00;

        if (empty($cartForm->cart)) {
            return 0;
        }

        // International shipping costs more
        if ($country !== 'United States') {
            return $baseShipping * 2;
        }

        return $baseShipping;
    }

    /**
     * Validate and apply a promo code
     *
     * @param ShoppingCartForm $cartForm
     * @param string $promoCode
     * @return bool
     */
    public function applyPromoCode(ShoppingCartForm $cartForm, string $promoCode): bool
    {
        // In a real app, you'd check against a database of valid promo codes
        // This is a simple example implementation
        $validPromoCodes = [
            'DISCOUNT10' => 0.1,
            'SALE20' => 0.2,
            'FREESHIP' => 0
        ];

        $promoCode = strtoupper($promoCode);

        if (array_key_exists($promoCode, $validPromoCodes)) {
            if ($promoCode === 'FREESHIP') {
                $cartForm->shipping = 0;
            } else {
                $discountRate = $validPromoCodes[$promoCode];
                $cartForm->discount = $cartForm->subtotal * $discountRate;
            }

            $cartForm->promoCodeApplied = true;
            $cartForm->total = $cartForm->subtotal + $cartForm->tax + $cartForm->shipping - $cartForm->discount;

            return true;
        }

        return false;
    }
}
