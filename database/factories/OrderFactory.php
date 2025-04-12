<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'farmer_id' => User::factory(),
            'order_number' => $this->faker->unique()->numerify('ORD-#####'),
            'status' => $this->faker->randomElement(['new', 'shipped', 'processing', 'delivered', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer', 'cash']),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
