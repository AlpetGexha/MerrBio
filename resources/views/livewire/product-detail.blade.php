<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session()->has('notification'))
        <div class="mb-6">
            <x-notification :type="session('notification.type')" :message="session('notification.message')" />
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            <!-- Product Image -->
            <div class="relative">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                     class="w-full h-96 object-cover rounded-lg">
                @if(!$product->canBeAddedToCart())
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100">
                            Out of Stock
                        </span>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ $product->name }}
                    </h1>
                    <p class="mt-2 text-2xl font-semibold text-indigo-600 dark:text-indigo-400">
                        ${{ number_format($product->price, 2) }}
                    </p>
                </div>

                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-zinc-600 dark:text-zinc-400">
                        {{ $product->description }}
                    </p>
                </div>

                <!-- Quick Info -->
                <div class="grid grid-cols-2 gap-4 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                    <div>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">SKU</span>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Availability</span>
                        @if($product->canBeAddedToCart())
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">
                                In Stock ({{ $product->stock_quantity }} available)
                            </p>
                        @else
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">Out of Stock</p>
                        @endif
                    </div>
                    <div>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Category</span>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $product->category?->name ?? 'Uncategorized' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Currency</span>
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $product->currency }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <button wire:click="addToCart"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ !$product->canBeAddedToCart() ? 'disabled' : '' }}>
                        Add to Cart
                    </button>
                    <a href="{{ route('products.index') }}"
                       class="px-6 py-3 border border-zinc-300 dark:border-zinc-600 rounded-md font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors duration-200">
                        Back to Products
                    </a>
                </div>

                <!-- Keywords -->
                @if(!empty($product->keywords))
                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Keywords</h3>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($product->keywords as $keyword)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200">
                                    {{ $keyword }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Additional Details -->
                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Created At</span>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $product->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Last Updated</span>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $product->updated_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Total Orders</span>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $product->orderItems()->count() }}
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">In Cart</span>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $product->cartItems()->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
