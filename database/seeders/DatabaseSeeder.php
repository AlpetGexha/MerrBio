<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();

        $roles = [
            'admin',
            'farmer',
            'customer',
        ];

        //        add roles on Spatie Role
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@example.com',
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'customer@example.com',
        ])->assignRole('customer');

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'farmer@example.com',
        ])->assignRole('farmer');

        Categorie::factory(20)->create();
        Location::factory(20)->create();

        $products = Product::factory(10)->create();
        $products_user = Product::factory(4)->recycle($user)->create();
        $order_product = Order::factory(20)
            ->state([
                'farmer_id' => $user->id,
            ])
            ->has(
                OrderItems::factory()->count(rand(2, 5))
                    ->state(fn (array $attributes, Order $order) => ['product_id' => $products->random(1)->first()->id]),
                'orderItems'
            )
            ->create();

        Order::factory(20)
            ->has(
                OrderItems::factory()->count(rand(2, 5))
                    ->state(fn (array $attributes, Order $order) => ['product_id' => $products->random(1)->first()->id]),
                'orderItems'
            )
            ->create();
    }
}
