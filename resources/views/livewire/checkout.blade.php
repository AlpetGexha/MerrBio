<div class="min-h-screen bg-white dark:bg-zinc-800">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-zinc-900 dark:text-zinc-100">
                <h2 class="text-2xl font-bold mb-6">Checkout</h2>

                <form wire:submit="placeOrder" class="space-y-6">
                    <!-- Shipping Address -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Shipping Address</h3>

                        @if($addresses->isNotEmpty())
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Select a saved address</label>
                                <select wire:model.live="selected_address_id" class="w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                                    <option value="">Enter new address</option>
                                    @foreach($addresses as $address)
                                        <option value="{{ $address->id }}">{{ $address->full_address }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="$wire.show_address_fields">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Street</label>
                                <input type="text" wire:model="shipping_address.street" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">City</label>
                                <input type="text" wire:model="shipping_address.city" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">State</label>
                                <input type="text" wire:model="shipping_address.state" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">ZIP Code</label>
                                <input type="text" wire:model="shipping_address.zip_code" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Country</label>
                                <input type="text" wire:model="shipping_address.country" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                        </div>

                        <div class="flex items-center" x-show="$wire.show_address_fields">
                            <input type="checkbox" wire:model="save_address" id="save_address" class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-700 text-indigo-600 focus:ring-indigo-500">
                            <label for="save_address" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">Save this address for future use</label>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="same_as_shipping" id="same_as_shipping" class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-700 text-indigo-600 focus:ring-indigo-500">
                            <label for="same_as_shipping" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">Same as shipping address</label>
                        </div>

                        @if(!$same_as_shipping)
                            <h3 class="text-lg font-semibold">Billing Address</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Street</label>
                                    <input type="text" wire:model="billing_address.street" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">City</label>
                                    <input type="text" wire:model="billing_address.city" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">State</label>
                                    <input type="text" wire:model="billing_address.state" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">ZIP Code</label>
                                    <input type="text" wire:model="billing_address.zip_code" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Country</label>
                                    <input type="text" wire:model="billing_address.country" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Method -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Payment Method</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" wire:model="payment_method" value="credit_card" id="credit_card" class="h-4 w-4 border-zinc-300 dark:border-zinc-700 text-indigo-600 focus:ring-indigo-500">
                                <label for="credit_card" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">Credit Card</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" wire:model="payment_method" value="paypal" id="paypal" class="h-4 w-4 border-zinc-300 dark:border-zinc-700 text-indigo-600 focus:ring-indigo-500">
                                <label for="paypal" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">PayPal</label>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Order Summary</h3>
                        <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4">
                            <div class="space-y-2">
                                @foreach($this->cartItems as $item)
                                    <div class="flex justify-between">
                                        <span class="text-zinc-600 dark:text-zinc-400">{{ $item->product->name }} x {{ $item->quantity }}</span>
                                        <span class="text-zinc-900 dark:text-zinc-100">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Total</span>
                                    <span class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($this->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
