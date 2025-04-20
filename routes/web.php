<?php

use App\Livewire\BecomeFarmer;
use App\Livewire\Checkout;
use App\Livewire\Dashboard;
use App\Livewire\OrderDetail;
use App\Livewire\OrderList;
use App\Livewire\ProductDetail;
use App\Livewire\ProductListing;
use App\Livewire\ShoppingCart;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return to_route('products.index');
})->name('home');

Route::get('/dashboard', function () {
    return to_route('products.index');
})->name('dashboard');

Route::get('/product', ProductListing::class)->name('products.index');
Route::get('/cart', ShoppingCart::class)->name('cart.index');
Route::get('/products/{product}', ProductDetail::class)->name('products.show');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/orders', OrderList::class)->name('orders.index');
    Route::get('/orders/{order}', OrderDetail::class)->name('orders.show');
});

// Dashboard Routes
// Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/become-farmer', BecomeFarmer::class)
    ->middleware(['auth'])
    ->name('become-farmer');

require __DIR__ . '/auth.php';
