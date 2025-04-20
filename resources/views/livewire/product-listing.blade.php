<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-64 space-y-6">
            <!-- Categories -->
            <div class="space-y-2">
                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Categories</h3>
                <select wire:model.live="filters.category"
                    class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range -->
            <div class="space-y-2">
                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Price Range</h3>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" wire:model.live.debounce.300ms="filters.min_price" placeholder="Min"
                        class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                    <input type="number" wire:model.live.debounce.300ms="filters.max_price" placeholder="Max"
                        class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                </div>
            </div>

            <!-- Stock Status -->
            <div class="space-y-2">
                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Availability</h3>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="filters.in_stock"
                        class="rounded border-zinc-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800">
                    <span class="ml-2 text-sm text-zinc-600 dark:text-zinc-400">In Stock Only</span>
                </label>
            </div>

            <!-- Sort Options -->
            <div class="space-y-2">
                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Sort By</h3>
                <select wire:model.live="filters.sort_by"
                    class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="created_at">Newest</option>
                    <option value="price">Price</option>
                    <option value="name">Name</option>
                </select>
                <select wire:model.live="filters.sort_direction"
                    class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
            </div>

            <!-- Reset Filters -->
            <button wire:click="resetFilters"
                class="w-full px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors duration-200">
                Reset Filters
            </button>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <!-- Search and Cart Section -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="flex-1 relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                        class="w-full pl-10 pr-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-zinc-100">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            @if (session()->has('notification'))
                <x-notification :type="session('notification.type')" :message="session('notification.message')" />
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
                        <a href="{{ route('products.show', $product) }}" class="block relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover">
                            @if (!$product->canBeAddedToCart())
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100">
                                        Out of Stock
                                    </span>
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                                <a href="{{ route('products.show', $product) }}"
                                    class="hover:text-indigo-600 dark:hover:text-indigo-400">
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
                                @if ($product->canBeAddedToCart())
                                    <span class="text-sm text-green-600 dark:text-green-400">
                                        {{ $product->stock_quantity }} available
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
                @empty

                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden p-4 grid grid-cols-1 place-items-center h-48">
                        <p class="text-center text-zinc-500 dark:text-zinc-400">
                            No products found matching your criteria.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
