<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8 text-zinc-900 dark:text-zinc-100">Shopping Cart</h1>

    @if(session()->has('notification'))
        <div class="mb-6">
            <x-notification :type="session('notification.type')" :message="session('notification.message')" />
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-6 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">Your cart is empty</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach($cartItems as $item)
                    <div class="p-6 flex items-center">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">{{ $item->product->name }}</h3>
                            <div class="mt-2 flex items-center space-x-4">
                                <div class="flex items-center">
                                    <label for="quantity-{{ $item->id }}" class="sr-only">Quantity</label>
                                    <div class="flex items-center border border-zinc-300 dark:border-zinc-600 rounded-md">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="px-3 py-1 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-l-md">
                                            <span class="sr-only">Decrease quantity</span>
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <input type="number"
                                               id="quantity-{{ $item->id }}"
                                               wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                               value="{{ $item->quantity }}"
                                               min="1"
                                               class="w-12 text-center bg-transparent border-x border-zinc-300 dark:border-zinc-600 py-1 text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="px-3 py-1 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-r-md">
                                            <span class="sr-only">Increase quantity</span>
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    ${{ number_format($item->product->price * $item->quantity, 2) }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="removeFromCart({{ $item->id }})"
                                class="ml-4 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                        Total: ${{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}
                    </span>
                    <a href="{{ route('checkout') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
