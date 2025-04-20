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
                <div class="relative">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    @if($product->is_in_stock)
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                In Stock
                            </span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">{{ $product->name }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ Str::limit($product->description, 100) }}</p>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($product->price, 2) }} {{ $product->currency }}</span>

                        <button wire:click="addToCart({{ $product->id }})"
                                class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-em-500 focus:ring-offset-2 dark:bg-emerald-500 dark:hover:bg-emerald-600">
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
