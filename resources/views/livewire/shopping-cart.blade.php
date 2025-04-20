<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-8 text-zinc-900 dark:text-zinc-100">Shopping Cart</h1>

    @if (count($this->cartItems) > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach ($this->cartItems as $item)
                    <div class="p-6"
                        x-data="{
                            quantity: {{ $item->quantity }},
                            price: {{ $item->product->price }},
                            currency: '{{ $item->product->currency }}',
                            debounceTimer: null,
                            updateQuantity() {
                                clearTimeout(this.debounceTimer);
                                this.debounceTimer = setTimeout(() => {
                                    this.$wire.updateQuantity({{ $item->id }}, this.quantity);
                                }, 1000);
                            },
                            increment() {
                                this.quantity++;
                                this.updateQuantity();
                            },
                            decrement() {
                                if (this.quantity > 1) {
                                    this.quantity--;
                                    this.updateQuantity();
                                }
                            },
                            get itemTotal() {
                                return (this.quantity * this.price).toFixed(2);
                            }
                        }">
                        <div class="flex items-center">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                class="w-20 h-20 object-cover rounded">

                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $item->product->name }}</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $item->product->description }}</p>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <button @click="decrement()"
                                        class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <input type="number"
                                        x-model="quantity"
                                        min="1"
                                        @input="updateQuantity()"
                                        class="w-16 text-center border-zinc-300 dark:border-zinc-600 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                                    <button @click="increment()"
                                        class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>

                                <span class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                    $<span x-text="itemTotal"></span> {{ $item->product->currency }}
                                </span>

                                <button wire:click="removeItem({{ $item->id }})"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-6 bg-zinc-50 dark:bg-zinc-800">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Total:
                        {{ number_format($this->total, 2) }} USD</span>
                    <button wire:click="checkout"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Your cart is empty</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Start adding some items to your cart</p>
            <div class="mt-6">
                @auth
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                        Continue Shopping
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                        Login to Continue Shopping
                    </a>
                @endauth
            </div>
        </div>
    @endif
</div>
