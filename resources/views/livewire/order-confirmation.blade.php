<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Order Confirmation</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Thank you for your order!</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Order Details</h2>
                    <dl class="mt-4 space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Order Number</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">#{{ $order->order_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Date</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $order->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100
                                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Shipping Address</h2>
                    <dl class="mt-4 space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Name</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $order->shipping_address['name'] ?? '' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Address</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $order->shipping_address['street'] ?? '' }}<br>
                                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip_code'] ?? '' }}<br>
                                {{ $order->shipping_address['country'] ?? '' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Order Items</h2>
                <div class="mt-4 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                            <div class="ml-4 flex-1">
                                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $item->product->name }}</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Quantity: {{ $item->qty }}</p>
                            </div>
                            <div class="ml-4 text-sm text-zinc-900 dark:text-zinc-100">
                                ${{ number_format($item->total, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400">Subtotal</dt>
                            <dd class="text-sm text-zinc-900 dark:text-zinc-100">${{ number_format($order->total_amount, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400">Shipping</dt>
                            <dd class="text-sm text-zinc-900 dark:text-zinc-100">${{ number_format($order->shipping, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400">Tax</dt>
                            <dd class="text-sm text-zinc-900 dark:text-zinc-100">${{ number_format($order->vat, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <dt class="text-base font-medium text-zinc-900 dark:text-zinc-100">Total</dt>
                            <dd class="text-base font-medium text-zinc-900 dark:text-zinc-100">${{ number_format($order->total_amount + $order->shipping + $order->vat, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700">
            <div class="flex justify-between items-center">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Continue Shopping
                </a>
                <a href="{{ route('orders.show', $order) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    View Order Details
                </a>
            </div>
        </div>
    </div>
</div>
