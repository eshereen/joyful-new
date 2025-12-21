<div class="space-y-10">
    @if($collections->isEmpty())
        <div class="p-8 text-center bg-white rounded-2xl shadow">
            <p class="text-lg font-medium text-gray-600">New collections are coming soon. Stay tuned! ✨</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            @foreach($collections as $collection)
                @php
                    $imageUrl = $collection->getFirstMediaUrl('main_image') ?: asset('imgs/logo.png');
                    $productCount = $collection->active_products_count ?? $collection->products_count ?? $collection->products_count ?? $collection->products()->where('products.active', true)->count();
                    $stock = (int) ($collection->stock ?? 0);
                    $inStock = $stock > 0;
                @endphp
                <div wire:key="collection-card-{{ $collection->id }}" class="relative overflow-hidden rounded-2xl shadow-lg group">
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $collection->name }}"
                        class="object-cover w-full h-80 transition-transform duration-700 group-hover:scale-105"
                        loading="lazy"
                        onerror="this.src='{{ asset('imgs/logo.png') }}'"
                    >
                    <div class="absolute inset-0 flex flex-col justify-end p-6 bg-linear-to-t from-black/80 via-black/60 to-transparent text-white">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="text-2xl font-bold">{{ $collection->name }}</h4>
                                @if($productCount)
                                    <p class="mt-1 text-sm text-white/80">{{ $productCount }} curated products</p>
                                @endif
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold uppercase bg-white/20 rounded-full backdrop-blur">
                                Collection
                            </span>
                        </div>
                        @if($collection->description)
                            <p class="mt-4 text-sm leading-relaxed text-white/80 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($collection->description), 140) }}
                            </p>
                        @endif
                        <div class="flex flex-wrap items-center justify-between gap-3 mt-6">
                            <div>
                                <p class="text-sm text-white/80">Starting from</p>
                                <p class="text-2xl font-semibold">EGP {{ number_format($collection->price ?? 0, 2) }}</p>
                                <p class="mt-1 text-xs font-semibold {{ $inStock ? 'text-green-200' : 'text-red-200' }}">
                                    @if($inStock)
                                        {{ $stock }} set{{ $stock === 1 ? '' : 's' }} available
                                    @else
                                        Out of stock
                                    @endif
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    wire:click="addCollectionToCart({{ $collection->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addCollectionToCart({{ $collection->id }})"
                                    @disabled(!$inStock)
                                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-dark-brown rounded-full shadow-lg transition hover:bg-opacity-90 disabled:opacity-60 disabled:cursor-not-allowed"
                                >
                                    <span wire:loading.remove wire:target="addCollectionToCart({{ $collection->id }})">
                                        {{ $inStock ? 'Add to Cart' : 'Unavailable' }}
                                    </span>
                                    <span wire:loading wire:target="addCollectionToCart({{ $collection->id }})" class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Adding...
                                    </span>
                                </button>
                                <a
                                    href="/product?collection={{ $collection->id }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-dark-brown bg-white rounded-full shadow hover:bg-gray-100"
                                >
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
