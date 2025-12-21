<div class="space-y-6">
    <!-- Search Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Search -->
        <div class="w-full md:w-96">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Search collections..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
            >
        </div>
    </div>

    <!-- Collections Grid -->
    @if($filteredCollections->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($filteredCollections as $collection)
                @php
                    $imageWebp = $collection->getFirstMediaUrl('main_image', 'large_webp');
                    $imageDefault = $collection->getFirstMediaUrl('main_image') ?: asset('imgs/logo.png');
                    $productsCount = $collection->products_count
                        ?? ($collection->relationLoaded('products') ? $collection->products->count() : 0);
                    $price = $collection->price ?? 0;
                    $stock = (int) ($collection->stock ?? 0);
                    $inStock = $stock > 0;

                    // Get first active product from collection
                    // Products are already filtered for active when loaded, so just get first
                    $firstProduct = null;
                    if ($collection->relationLoaded('products') && $collection->products->isNotEmpty()) {
                        // Products are already filtered for active=true in FrontendController
                        $firstProduct = $collection->products->first();
                    } else {
                        // Fallback: query if not loaded
                        $firstProduct = $collection->products()->where('active', true)->first();
                    }
                @endphp
                <div wire:key="collections-grid-card-{{ $collection->id }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Collection Image -->
                    <div class="relative group">
                        @if($collection->media->count() > 0)
                            <picture class="w-full h-64 block">
                                @if($imageWebp)
                                    <source srcset="{{ $imageWebp }}" type="image/webp">
                                @endif
                                <img src="{{ $imageDefault }}"
                                     alt="{{ $collection->name }}"
                                     class="w-full h-64 object-cover"
                                     width="400"
                                     height="400"
                                     loading="lazy"
                                     decoding="async"
                                     onerror="this.src='{{ asset('imgs/logo.png') }}'">
                            </picture>
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif

                        <!-- Quick Actions Overlay -->
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-black/50 transition-all duration-300 flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a
                                    href="{{ route('product.show', '') }}?collection={{ $collection->id }}"
                                    class="bg-white text-gray-900 px-6 py-3 rounded-full hover:bg-red-600 hover:text-white transition-colors font-medium"
                                >
                                    View Collection
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Info -->
                    <div class="p-4 space-y-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $collection->name }}</h3>

                            @if($collection->description)
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $collection->description }}</p>
                            @endif
                        </div>

                        <!-- Product Count & Price -->
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>
                                {{ $productsCount }} {{ \Illuminate\Support\Str::plural('product', $productsCount) }}
                                •
                                <span class="{{ $inStock ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $inStock ? ($stock . ' in stock') : 'Out of stock' }}
                                </span>
                            </span>
                            @if($price > 0)
                                <span class="font-semibold text-gray-900">EGP {{ number_format($price, 2) }}</span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button
                                wire:click="addCollectionToCart({{ $collection->id }})"
                                wire:loading.attr="disabled"
                                wire:target="addCollectionToCart({{ $collection->id }})"
                                @disabled(!$inStock)
                                class="w-full inline-flex items-center justify-center gap-2 bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-800 transition disabled:opacity-70 disabled:cursor-not-allowed"
                            >
                                <span wire:loading.remove wire:target="addCollectionToCart({{ $collection->id }})">
                                    {{ $inStock ? 'Add to Cart' : 'Unavailable' }}
                                </span>
                                <span wire:loading wire:target="addCollectionToCart({{ $collection->id }})" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Adding...
                                </span>
                            </button>

                            @if($firstProduct && $firstProduct->slug)
                                <a
                                    href="{{ route('product.show', $firstProduct->slug) }}?collection={{ $collection->id }}"
                                    class="w-full inline-flex items-center justify-center border border-gray-900 text-gray-900 py-2 px-4 rounded-lg hover:bg-gray-900 hover:text-white transition text-center"
                                >
                                    View Details
                                </a>
                            @else
                                <a
                                href="/product?collection={{ $collection->id }}"

                                    class="w-full inline-flex items-center justify-center border border-gray-900 text-gray-900 py-2 px-4 rounded-lg hover:bg-gray-900 hover:text-white transition text-center"
                                >
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Collections Found -->
        <div class="text-center py-12">
            <div class="text-gray-500">
                <i class="fas fa-search text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No collections found</h3>
                <p class="text-gray-600">
                    @if($search)
                        No collections match your search "{{ $search }}"
                    @else
                        No collections available at the moment.
                    @endif
                </p>
            </div>
        </div>
    @endif
</div>
