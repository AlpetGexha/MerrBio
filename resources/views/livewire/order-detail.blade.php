<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
        <!-- Order Header -->
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Order #{{ $order->order_number }}</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Placed on {{ $order->created_at->format('F j, Y') }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100
                        @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Order Items</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-20 h-20">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover rounded">
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $item->product->name }}</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Quantity: {{ $item->qty }}</p>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">${{ number_format($item->price * $item->qty, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Order Summary</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
                    <span class="text-zinc-900 dark:text-zinc-100">${{ number_format($order->total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
                    <span class="text-zinc-900 dark:text-zinc-100">${{ number_format($order->shipping, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Tax</span>
                    <span class="text-zinc-900 dark:text-zinc-100">${{ number_format($order->vat, 2) }}</span>
                </div>
                <div class="flex justify-between border-t border-zinc-200 dark:border-zinc-700 pt-2 mt-2">
                    <span class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Total</span>
                    <span class="text-lg font-medium text-zinc-900 dark:text-zinc-100">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Shipping Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Shipping Address</h3>
                    <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                        @if(is_array($order->shipping_address))
                            {{ $order->shipping_address['street'] ?? '' }}<br>
                            {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip_code'] ?? '' }}<br>
                            {{ $order->shipping_address['country'] ?? '' }}
                        @else
                            {{ $order->shipping_address }}
                        @endif
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Billing Address</h3>
                    <p class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                        @if(is_array($order->billing_address))
                            {{ $order->billing_address['street'] ?? '' }}<br>
                            {{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['zip_code'] ?? '' }}<br>
                            {{ $order->billing_address['country'] ?? '' }}
                        @else
                            {{ $order->billing_address }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="p-6">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Payment Information</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Payment Method</span>
                    <span class="text-zinc-900 dark:text-zinc-100">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-600 dark:text-zinc-400">Payment Status</span>
                    <span class="text-zinc-900 dark:text-zinc-100">{{ ucfirst($order->payment_status) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
