<?php

namespace Database\Factories;

use App\Models\Categorie;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
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
            'categorie_id' => Categorie::factory(),
            'name' => $this->faker->word(),
            'body' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['active', 'inactive', 'out_of_stock']),
        ];
    }
}
