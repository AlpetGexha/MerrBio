<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8 text-zinc-900 dark:text-zinc-100">Products</h1>

    @if(session()->has('notification'))
        <x-notification :type="session('notification.type')" :message="session('notification.message')" />
    @endif

    <!-- Search and Filters -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search products..."
                    class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100"
                >
            </div>

            <!-- Filters -->
            <div class="flex gap-4">
                <select
                    wire:model.live="filters.category"
                    class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100"
                >
                    <option value="">All Categories</option>
                    @foreach($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filters.sort_by" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="created_at">Newest</option>
                    <option value="price">Price</option>
                    <option value="name">Name</option>
                </select>

                <select wire:model.live="filters.sort_direction" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>

                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="filters.in_stock" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="ml-2">In Stock Only</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($this->products as $product)
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
                <a href="{{ route('products.show', $product) }}" class="block relative">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         class="w-full h-48 object-cover">
                </a>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                        <a href="{{ route('products.show', $product) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                            {{ $product->name }}
                        </a>
                    </h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ Str::limit($product->description, 100) }}
                    </p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        @if($product->canBeAddedToCart())
                            <span class="text-sm text-green-600 dark:text-green-400">
                                {{ $product->stock_quantity }} available
                            </span>
                        @else
                            <span class="text-sm text-red-600 dark:text-red-400">
                                Out of Stock
                            </span>
                        @endif
                    </div>
                    <div class="mt-4">
                        <button wire:click="addToCart({{ $product->id }})"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-md font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ !$product->canBeAddedToCart() ? 'disabled' : '' }}>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $this->products->links() }}
    </div>
</div>
