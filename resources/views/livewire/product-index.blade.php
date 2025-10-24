<div>
<div class="{{ request()->routeIs('home') ? '' : 'container mx-auto px-4 py-8 my-20' }}">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg border border-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg border border-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if(request()->routeIs('products.index'))
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-red-600">Store</h1>
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative">
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search products..."
                       class="px-4 py-2 pl-10 rounded-lg border focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <svg class="absolute top-2.5 left-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <!-- Sort -->
            <select wire:model.live="sortBy" class="px-3 py-2 rounded-lg border focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="newest">Newest</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
            </select>
        </div>
    </div>

    @endif
    @if(!request()->routeIs('home'))
    @if($currencyCode !== 'USD')
    <div class="p-4 mb-6 bg-green-50 rounded-lg border border-green-200">
        <div class="text-sm text-center text-green-800">
            @if($isAutoDetected)
                Prices automatically converted to {{ $currencyCode }} ({{ $currencySymbol }}) based on your location
            @else
                Prices converted to {{ $currencyCode }} ({{ $currencySymbol }})
            @endif
        </div>
    </div>
    @endif
    @endif

    @if(request()->routeIs('home'))
    <!-- Slider view for home page -->
    @if($products && $products->count() > 0)
    <div class="product-slider-container" x-data="{
        isPaused: false,
        init() {
            this.$nextTick(() => {
                this.initSlider();
            });
        },
        initSlider() {
            const slider = this.$refs.productSlider;
            if (slider) {
                slider.style.animationDuration = '30s';
                slider.style.animationPlayState = this.isPaused ? 'paused' : 'running';
            }
        },
        togglePause() {
            this.isPaused = !this.isPaused;
            const slider = this.$refs.productSlider;
            if (slider) {
                slider.style.animationPlayState = this.isPaused ? 'paused' : 'running';
            }
        },
        speedUp() {
            this.scrollSpeed = Math.min(3, this.scrollSpeed + 0.5);
            this.$refs.slider.style.animationDuration = (30 / this.scrollSpeed) + 's';
        },
        slowDown() {
            this.scrollSpeed = Math.max(0.5, this.scrollSpeed - 0.5);
            this.$refs.slider.style.animationDuration = (30 / this.scrollSpeed) + 's';
        }
    }">
        <!-- Control Buttons -->
        <div class="slider-controls">
            <!-- Play/Pause Button -->
            <button @click="togglePause()"
                    class="p-3 rounded-full shadow-lg transition-all duration-200 bg-white/90 hover:bg-white hover:scale-110"
                    :aria-label="isPaused ? 'Play' : 'Pause'">
                <svg x-show="!isPaused" class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                </svg>
                <svg x-show="isPaused" class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>

            <!-- Speed Down Button -->
            <button @click="slowDown()"
                    class="p-3 rounded-full shadow-lg transition-all duration-200 bg-white/90 hover:bg-white hover:scale-110"
                    aria-label="Slow down">
                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Speed Up Button -->
            <button @click="speedUp()"
                    class="p-3 rounded-full shadow-lg transition-all duration-200 bg-white/90 hover:bg-white hover:scale-110"
                    aria-label="Speed up">
                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

       <!-- Products Container -->
       <div wire:ignore class="product-slider-container">
           <div class="product-slider"
                x-ref="productSlider"
                @mouseenter="isPaused = true; $refs.productSlider.style.animationPlayState = 'paused'"
                @mouseleave="isPaused = false; $refs.productSlider.style.animationPlayState = 'running'">
            <!-- First set of products -->
            <div class="flex px-3 space-x-6">
            @foreach($products as $product)
        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md transition hover:shadow-lg">
            <div class="relative overflow-hidden aspect-[4/5] product-image-container"
                 style="cursor: pointer;"
                 onmouseenter="this.querySelector('.main-image').style.opacity='0'; this.querySelector('.gallery-image').style.opacity='1';"
                 onmouseleave="this.querySelector('.main-image').style.opacity='1'; this.querySelector('.gallery-image').style.opacity='0';"
                 onclick="window.location.href='{{ route('product.show', $product->slug) }}'">

                <!-- Badges -->
                <div class="flex absolute left-0 top-2 z-30 flex-col gap-1">
                    <!-- Best Seller Badge -->
                    @if($this->isBestSeller($product->id))
                    <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-green-600 rounded">
                        Best Seller
                    </span>
                    @endif

                    <!-- Flash Sale Badge -->
                    @if($product->compare_price > 0)
                    <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-red-600 rounded">
                        Flash Sale
                    </span>
                    @endif
                </div>

                <div class="block relative w-full h-full">
                    {{-- Main image --}}
                    @php
                        $mainImage = $product->getFirstMediaUrl('main_image') ?: '/imgs/joyful.png';
                    @endphp
                    <img src="{{ $mainImage }}"
                         alt="{{ $product->name }}"
                         class="object-cover w-full h-full transition-opacity duration-500 main-image"
                         style="opacity: 1; transition: opacity 0.5s ease;"
                         width="300"
                         height="300"
                         loading="lazy">

                    {{-- Gallery image (if exists) --}}
                    @php
                        $galleryImages = $product->getMedia('product_images');
                        $galleryImage = null;
                        foreach($galleryImages as $img) {
                            if($img->getUrl() !== $mainImage) {
                                $galleryImage = $img->getUrl();
                                break;
                            }
                        }
                    @endphp
                    @if($galleryImage)
                        <img src="{{ $galleryImage }}"
                             alt="{{ $product->name }}"
                             class="object-cover absolute top-0 left-0 w-full h-full transition-opacity duration-500 gallery-image"
                             style="opacity: 0; z-index: 2; transition: opacity 0.5s ease;"
                             width="300"
                             height="300"
                             loading="lazy">
                    @endif
                </div>

                <!-- Wishlist Button -->
                @auth
                <div class="absolute top-2 right-2 z-20">
                    <button wire:click="toggleWishlist({{ $product->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleWishlist({{ $product->id }})"
                            onclick="event.stopPropagation()"
                            class="p-2 bg-white rounded-full shadow-md transition-colors hover:bg-gray-50"
                            data-product-id="{{ $product->id }}"
                            title="{{ in_array($product->id, $wishlistProductIds) ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <svg class="w-5 h-5 {{ in_array($product->id, $wishlistProductIds) ? 'text-dark-brown fill-current' : 'text-gray-600' }}"
                             fill="{{ in_array($product->id, $wishlistProductIds) ? 'currentColor' : 'none' }}"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                    <span wire:loading wire:target="toggleWishlist({{ $product->id }})" class="absolute top-2 right-2 p-2">
                        <svg class="w-5 h-5 text-dark-brown animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </div>
                @else
                <a href="{{ route('login') }}" class="absolute top-2 right-2 z-20 p-2 bg-white rounded-full shadow-md transition-colors hover:bg-gray-50" title="Login to add to wishlist">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>
                @endauth
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <a href="{{ route('product.show', $product->slug) }}"
                           class="text-base font-semibold hover:text-red-600">
                            {{ $product->name }}
                        </a>
                        @if($product->category)
                            <p class="pt-3 text-sm text-gray-600">{{ $product->category->name }}</p>
                        @endif
                    </div>
                    @if($product->compare_price > 0)
                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                        -{{ $product->discount_percentage }}%
                    </span>
                    @endif
                </div>

                <div class="flex justify-between items-center mt-2">
                    <div>
                        <span class="text-base font-bold">{{ $currencySymbol }}{{ number_format($product->converted_price ?? $product->price, 2) }}</span>
                        @if($product->compare_price > 0)
                        <span class="ml-2 text-sm text-gray-500 line-through">
                            {{ $currencySymbol }}{{ number_format($product->converted_compare_price ?? $product->compare_price, 2) }}
                        </span>
                        @endif
                    </div>

                    {{-- COMMENTED OUT: Add to Cart buttons in main slider to prevent duplication --}}
                    {{-- @if($product->has_variants)
                        @if($product->quantity > 0)
                        <button wire:click="openVariantModal({{ $product->id }})"
                                class="p-2 text-white rounded-full border-2 transition add-to-cart bg-gray-950 hover:bg-white hover:text-gray-950 border-gray-950">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:text-gray-950" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @else
                        <button disabled
                                class="p-2 text-gray-500 bg-gray-400 rounded-full border-2 border-gray-400 opacity-50 cursor-not-allowed add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @endif
                    @else
                        @if($product->quantity > 0)
                        <button wire:click="addSimpleProductToCart({{ $product->id }}, 1)"
                                wire:loading.attr="disabled"
                                wire:target="addSimpleProductToCart({{ $product->id }}, 1)"
                                class="p-2 text-white rounded-full border-2 transition add-to-cart bg-gray-950 hover:bg-white hover:text-gray-950">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:text-gray-950" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @else
                        <button disabled
                                class="p-2 text-gray-500 bg-gray-400 rounded-full border-2 border-gray-400 opacity-50 cursor-not-allowed add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @endif
                    @endif --}}

                </div>
            </div>
        </div>
            @endforeach
        </div>
        <!-- Duplicate set for continuous scrolling - COMMENTED OUT -->
        {{-- <div class="flex px-3 space-x-6">
            @foreach($products as $product)
        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md transition hover:shadow-lg">
            <div class="relative overflow-hidden aspect-[4/5] product-image-container"
                 style="cursor: pointer;"
                 onmouseenter="this.querySelector('.main-image').style.opacity='0'; this.querySelector('.gallery-image').style.opacity='1';"
                 onmouseleave="this.querySelector('.main-image').style.opacity='1'; this.querySelector('.gallery-image').style.opacity='0';"
                 onclick="window.location.href='{{ route('product.show', $product->slug) }}'">

                <!-- Badges -->
                <div class="flex absolute left-0 top-2 z-30 flex-col gap-1">
                    @if($this->isBestSeller($product->id))
                    <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-green-600 rounded">
                        Best Seller
                    </span>
                    @endif

                    @if($product->compare_price > 0)
                    <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-red-600 rounded">
                        Flash Sale
                    </span>
                    @endif
                </div>

                <div class="block relative w-full h-full">
                    @php
                        $mainImage = $product->getFirstMediaUrl('main_image') ?: '/imgs/joyful.png';
                    @endphp
                    <img src="{{ $mainImage }}"
                         alt="{{ $product->name }}"
                         class="object-cover w-full h-full transition-opacity duration-500 main-image"
                         style="opacity: 1; transition: opacity 0.5s ease;"
                         width="300"
                         height="300"
                         loading="lazy">

                    @php
                        $galleryImages = $product->getMedia('product_images');
                        $galleryImage = null;
                        foreach($galleryImages as $img) {
                            if($img->getUrl() !== $mainImage) {
                                $galleryImage = $img->getUrl();
                                break;
                            }
                        }
                    @endphp
                    @if($galleryImage)
                        <img src="{{ $galleryImage }}"
                             alt="{{ $product->name }}"
                             class="object-cover absolute top-0 left-0 w-full h-full transition-opacity duration-500 gallery-image"
                             style="opacity: 0; z-index: 2; transition: opacity 0.5s ease;"
                             width="300"
                             height="300"
                             loading="lazy">
                    @endif
                </div>

                @auth
                <div class="absolute top-2 right-2 z-20">
                    <button wire:click="toggleWishlist({{ $product->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleWishlist({{ $product->id }})"
                            onclick="event.stopPropagation()"
                            class="p-2 bg-white rounded-full shadow-md transition-colors hover:bg-gray-50"
                            data-product-id="{{ $product->id }}"
                            title="{{ in_array($product->id, $wishlistProductIds) ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <svg class="w-5 h-5 {{ in_array($product->id, $wishlistProductIds) ? 'text-dark-brown fill-current' : 'text-gray-600' }}"
                             fill="{{ in_array($product->id, $wishlistProductIds) ? 'currentColor' : 'none' }}"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                    <span wire:loading wire:target="toggleWishlist({{ $product->id }})" class="absolute top-2 right-2 p-2">
                        <svg class="w-5 h-5 text-dark-brown animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </div>
                @else
                <a href="{{ route('login') }}" class="absolute top-2 right-2 z-20 p-2 bg-white rounded-full shadow-md transition-colors hover:bg-gray-50" title="Login to add to wishlist">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>
                @endauth
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <a href="{{ route('product.show', $product->slug) }}"
                           class="text-base font-semibold hover:text-red-600">
                            {{ $product->name }}
                        </a>
                        @if($product->category)
                            <p class="pt-3 text-sm text-gray-600">{{ $product->category->name }}</p>
                        @endif
                    </div>
                    @if($product->compare_price > 0)
                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                        -{{ $product->discount_percentage }}%
                    </span>
                    @endif
                </div>

                <div class="flex justify-between items-center mt-2">
                    <div>
                        <span class="text-base font-bold">{{ $currencySymbol }}{{ number_format($product->converted_price ?? $product->price, 2) }}</span>
                        @if($product->compare_price > 0)
                        <span class="ml-2 text-sm text-gray-500 line-through">
                            {{ $currencySymbol }}{{ number_format($product->converted_compare_price ?? $product->compare_price, 2) }}
                        </span>
                        @endif
                    </div>

                    @if($product->has_variants)
                        @if($product->quantity > 0)
                        <button wire:click="openVariantModal({{ $product->id }})"
                                class="p-2 text-white rounded-full border-2 transition add-to-cart bg-gray-950 hover:bg-white hover:text-gray-950 border-gray-950">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:text-gray-950" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @else
                        <button disabled
                                class="p-2 text-gray-500 bg-gray-400 rounded-full border-2 border-gray-400 opacity-50 cursor-not-allowed add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @endif
                    @else
                        @if($product->quantity > 0)
                        <button wire:click="addSimpleProductToCart({{ $product->id }}, 1)"
                                wire:loading.attr="disabled"
                                wire:target="addSimpleProductToCart({{ $product->id }}, 1)"
                                class="p-2 text-white rounded-full border-2 transition add-to-cart bg-gray-950 hover:bg-white hover:text-gray-950">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:text-gray-950" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @else
                        <button disabled
                                class="p-2 text-gray-500 bg-gray-400 rounded-full border-2 border-gray-400 opacity-50 cursor-not-allowed add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
            @endforeach
        </div>

    </div>
    </div>
    @else
        <!-- No products message -->
        <div class="py-12 text-center">
            <div class="text-gray-500">
                <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Check back soon for new products!</p>
            </div>
        </div>
    @endif
    @else
    <!-- Grid view for other pages (not home) -->
    @if(!request()->routeIs('home'))
    <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4">
        @if($products && $products->count() > 0)
            @foreach($products as $product)
        <div class="overflow-hidden bg-white rounded-lg shadow-md transition hover:shadow-lg">
            <div class="relative overflow-hidden aspect-[4/5] product-image-container"
                 style="cursor: pointer;"
                 onmouseenter="this.querySelector('.main-image').style.opacity='0'; this.querySelector('.gallery-image').style.opacity='1';"
                 onmouseleave="this.querySelector('.main-image').style.opacity='1'; this.querySelector('.gallery-image').style.opacity='0';"
                 onclick="window.location.href='{{ route('product.show', $product->slug) }}'">

                <!-- Badges -->
                <div class="flex absolute left-0 top-2 z-30 flex-col gap-1">
                    <!-- Best Seller Badge -->
                    @if($this->isBestSeller($product->id))
                    <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-green-600 rounded">
                        Best Seller
                    </span>
                    @endif

                    <!-- Flash Sale Badge -->
                    @if($product->compare_price > 0)
                    <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-red-600 rounded">
                        Flash Sale
                    </span>
                    @endif
                </div>

                <div class="block relative w-full h-full">

                    @php
                        $mainImage = $product->getFirstMediaUrl('main_image') ?: '/imgs/Joyful.png';
                    @endphp
                    <img src="{{ $mainImage }}"
                         alt="{{ $product->name }}"
                         class="object-cover w-full h-full transition-opacity duration-500 main-image"
                         style="opacity: 1; transition: opacity 0.5s ease;"
                         width="300"
                         height="300"
                         loading="lazy">


                    @php
                        $galleryImages = $product->getMedia('product_images');
                        $galleryImage = null;
                        foreach($galleryImages as $img) {
                            if($img->getUrl() !== $mainImage) {
                                $galleryImage = $img->getUrl();
                                break;
                            }
                        }
                    @endphp
                    @if($galleryImage)
                        <img src="{{ $galleryImage }}"
                             alt="{{ $product->name }}"
                             class="object-cover absolute top-0 left-0 w-full h-full transition-opacity duration-500 gallery-image"
                             style="opacity: 0; z-index: 2; transition: opacity 0.5s ease;"
                             width="300"
                             height="300"
                             loading="lazy">
                    @endif
                </div>

                <!-- Wishlist Button -->
                @auth
                <div class="absolute top-2 right-2 z-20">
                    <button wire:click="toggleWishlist({{ $product->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleWishlist({{ $product->id }})"
                            onclick="event.stopPropagation()"
                            class="p-2 bg-white rounded-full shadow-md transition-colors hover:bg-gray-50"
                            data-product-id="{{ $product->id }}"
                            title="{{ in_array($product->id, $wishlistProductIds) ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <svg class="w-5 h-5 {{ in_array($product->id, $wishlistProductIds) ? 'text-dark-brown fill-current' : 'text-gray-600' }}"
                             fill="{{ in_array($product->id, $wishlistProductIds) ? 'currentColor' : 'none' }}"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                    <span wire:loading wire:target="toggleWishlist({{ $product->id }})" class="absolute top-2 right-2 p-2">
                        <svg class="w-5 h-5 text-dark-brown animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </div>
                @else
                <a href="{{ route('login') }}" class="absolute top-2 right-2 z-20 p-2 bg-white rounded-full shadow-md transition-colors hover:bg-gray-50" title="Login to add to wishlist">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>
                @endauth
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <a href="{{ route('product.show', $product->slug) }}"
                           class="text-base font-semibold hover:text-red-600">
                            {{ $product->name }}
                        </a>
                        @if($product->category)
                            <p class="pt-3 text-sm text-gray-600">{{ $product->category->name }}</p>
                        @endif
                    </div>
                    @if($product->compare_price > 0)
                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                        -{{ $product->discount_percentage }}%
                    </span>
                    @endif
                </div>

                <div class="flex justify-between items-center mt-2">
                    <div>
                        <span class="text-base font-bold">{{ $currencySymbol }}{{ number_format($product->converted_price ?? $product->price, 2) }}</span>
                        @if($product->compare_price > 0)
                        <span class="ml-2 text-sm text-gray-500 line-through">
                            {{ $currencySymbol }}{{ number_format($product->converted_compare_price ?? $product->compare_price, 2) }}
                        </span>
                        @endif
                    </div>

                    @if($product->has_variants)
                        @if($product->quantity > 0)
                        <button wire:click="openVariantModal({{ $product->id }})"
                                class="p-2 text-white rounded-full border-2 transition add-to-cart bg-gray-950 hover:bg-white hover:text-gray-950 border-gray-950">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:text-gray-950" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @else
                        <button disabled
                                class="p-2 text-gray-500 bg-gray-400 rounded-full border-2 border-gray-400 opacity-50 cursor-not-allowed add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @endif
                    @else
                        @if($product->quantity > 0)
                        <button wire:click="addSimpleProductToCart({{ $product->id }}, 1)"
                                wire:loading.attr="disabled"
                                wire:target="addSimpleProductToCart({{ $product->id }}, 1)"
                                class="p-2 text-white rounded-full border-2 transition add-to-cart bg-gray-950 hover:bg-white hover:text-gray-950">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:text-gray-950" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @else
                        <button disabled
                                class="p-2 text-gray-500 bg-gray-400 rounded-full border-2 border-gray-400 opacity-50 cursor-not-allowed add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
            @endforeach
        </div> --}}
        {{-- @else
            <div class="col-span-full py-12 text-center">
                <div class="text-gray-500">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                </div>
            </div>
        @endif --}}
    </div>
    @endif
    @endif
    @if(request()->routeIs('products.index') && $products && method_exists($products, 'links'))
    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Variant Selection Modal -->
@if($showVariantModal)
<div class="flex fixed inset-0 z-50 justify-center items-center p-4 bg-black/50" wire:key="variant-modal-{{ $selectedProduct?->id ?? 'none' }}">
    <div class="p-6 w-full max-w-md bg-white rounded-lg shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Select Options</h3>
            <button wire:click="$set('showVariantModal', false)" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        @if($selectedProduct)
        <!-- Variant Options -->
        @if($selectedProduct->variants && $selectedProduct->variants->isNotEmpty())
        @php
            $allSizes = $selectedProduct->variants->unique('size')->pluck('size')->sort();
            $allWickTypes = $selectedProduct->variants->unique('wick_type')->pluck('wick_type');
            $selectedSize = $selectedVariant ? $selectedVariant->size : null;
            $selectedWickType = $selectedVariant ? $selectedVariant->wick_type : null;
        @endphp

        <!-- Size Selection -->
        <div class="mb-4">
            <h4 class="mb-2 text-sm font-medium text-gray-700">Size</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($allSizes as $size)
                @php
                    $hasStock = $selectedProduct->variants->where('size', $size)->where('stock', '>', 0)->isNotEmpty();
                    $variantForSize = $selectedProduct->variants->where('size', $size)->first();
                @endphp
                <button wire:click="selectVariant('{{ $variantForSize->id }}')"
                        {{ !$hasStock ? 'disabled' : '' }}
                        class="px-4 py-2 border rounded-md text-sm transition-all duration-200 {{ !$hasStock ? 'bg-gray-200 text-gray-400 cursor-not-allowed opacity-50' : ($selectedSize == $size ? 'ring-2 ring-gray-900 ring-offset-2 bg-gray-900 text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50') }}">
                    {{ $size }}g
                </button>
                @endforeach
            </div>
        </div>

        <!-- Wick Type Selection -->
        <div class="mb-4">
            <h4 class="mb-2 text-sm font-medium text-gray-700">Wick Type</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($allWickTypes as $wickType)
                @php
                    $isAvailable = $selectedSize ? $selectedProduct->variants->where('size', $selectedSize)->where('wick_type', $wickType)->where('stock', '>', 0)->isNotEmpty() : $selectedProduct->variants->where('wick_type', $wickType)->where('stock', '>', 0)->isNotEmpty();
                    $variantForWickType = $selectedSize ? $selectedProduct->variants->where('size', $selectedSize)->where('wick_type', $wickType)->first() : $selectedProduct->variants->where('wick_type', $wickType)->first();
                @endphp
                <button wire:click="selectVariant('{{ $variantForWickType->id }}')"
                        {{ !$isAvailable ? 'disabled' : '' }}
                        class="px-4 py-2 border rounded-md text-sm transition-all duration-200 {{ !$isAvailable ? 'bg-gray-200 text-gray-400 cursor-not-allowed opacity-50' : ($selectedWickType === $wickType ? 'ring-2 ring-gray-900 ring-offset-2 bg-gray-900 text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50') }}">
                    {{ ucfirst($wickType) }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quantity Selector -->
        @if($selectedVariant)
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Quantity</label>
            <div class="flex items-center w-32 rounded-lg border">
                <button type="button"
                        onclick="decrementModalQuantity()"
                        class="px-3 py-2 bg-white rounded-l-lg transition-colors hover:bg-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </button>
                <input type="number"
                       wire:model.defer="quantity"
                       min="1"
                       max="10"
                       class="w-16 text-center border-0 focus:ring-0 focus:outline-none"
                       id="modal-quantity-input"
                       onchange="updateQuantityFromInput(this.value)">
                <button type="button"
                        onclick="incrementModalQuantity()"
                        class="px-3 py-2 bg-white rounded-r-lg transition-colors hover:bg-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Price Display -->
        @if($selectedVariant)
        <div class="p-4 mb-4 bg-gray-50 rounded-lg border-2 border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-sm text-gray-600">Price:</span>
                    <div class="mt-1 text-sm text-gray-500">
                        Stock: {{ $selectedVariant->stock }} available
                    </div>
                </div>
                <span class="text-2xl font-bold text-gray-900">
                    {{ $currencySymbol }}{{ number_format($selectedVariant->converted_price ?? $selectedVariant->price, 2) }}
                </span>
            </div>
        </div>
        @endif


        <!-- Action Buttons -->
        <div class="flex justify-end mt-3 space-x-3">
            <button wire:click="$set('showVariantModal', false)"
                    class="px-4 py-2 rounded border transition-colors hover:bg-white">
                Cancel
            </button>
            <button wire:click="addToCart"
                    wire:loading.attr="disabled"
                    wire:target="addToCart"
                    class="px-4 py-2 text-white rounded transition-colors {{ $selectedVariant && $quantity > 0 && $selectedVariant->stock > 0 && $quantity <= $selectedVariant->stock ? 'bg-gray-950 border-2 border-gray-950 hover:bg-white hover:text-gray-950' : 'bg-gray-400 cursor-not-allowed' }}"
                    {{ !$selectedVariant || $quantity <= 0 || $selectedVariant->stock <= 0 || $quantity > ($selectedVariant ? $selectedVariant->stock : 0) ? 'disabled' : '' }}>
                <!-- DEBUG: selectedVariant: {{ $selectedVariant ? 'YES' : 'NO' }} -->
                <!-- DEBUG: quantity: {{ $quantity }} -->
                <!-- DEBUG: variant stock: {{ $selectedVariant ? $selectedVariant->stock : 'NULL' }} -->
                <!-- DEBUG: button disabled: {{ !$selectedVariant || $quantity <= 0 || $quantity > ($selectedVariant ? $selectedVariant->stock : 0) ? 'YES' : 'NO' }} -->
                <!-- DEBUG: condition1 (!selectedVariant): {{ !$selectedVariant ? 'YES' : 'NO' }} -->
                <!-- DEBUG: condition2 (quantity <= 0): {{ $quantity <= 0 ? 'YES' : 'NO' }} -->
                <!-- DEBUG: condition3 (quantity > stock): {{ $quantity > ($selectedVariant ? $selectedVariant->stock : 0) ? 'YES' : 'NO' }} -->
                <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
                <span wire:loading wire:target="addToCart">
                    <svg class="inline-block mr-2 -ml-1 w-4 h-4 text-white animate-spin hover:text-gray-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Adding...
                </span>
            </button>
        </div>
        @endif
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product index page loaded, setting up quantity button listeners...');

    // Function to update quantity input value
    function updateModalQuantityInput() {
        const quantityInput = document.getElementById('modal-quantity-input');
        if (quantityInput) {
            // Get the current quantity from Livewire
            const currentQuantity = @this.quantity || 1;
            if (quantityInput.value !== currentQuantity.toString()) {
                quantityInput.value = currentQuantity;
                console.log('Updated modal quantity input to:', currentQuantity);
            }
        }
    }

    // Listen for Livewire events
    window.addEventListener('livewire:init', () => {
        console.log('Livewire initialized on product index');
    });

    // Update input when Livewire processes messages
    Livewire.hook('message.processed', (message, component) => {
        if (component.fingerprint.name === 'product-index') {
            console.log('Product index component updated, checking for quantity changes');
            setTimeout(updateModalQuantityInput, 50);
        }
    });

    // Also update when the modal opens
    window.addEventListener('modal-opened', function() {
        setTimeout(updateModalQuantityInput, 100);
    });

    // Listen for currency change events
    window.addEventListener('currency-changed', function(e) {
        console.log('Currency change event received:', e.detail);
        // Trigger the Livewire method to refresh currency
        @this.call('handleCurrencyChange', e.detail);
    });

    // Listen for Livewire updates to showVariantModal
    Livewire.hook('message.processed', (message, component) => {
        if (component.fingerprint.name === 'product-index') {
            // Check if modal just opened
            if (@this.showVariantModal) {
                console.log('Variant modal opened, initializing quantity');
                setTimeout(() => {
                    initializeModalQuantity();
                }, 100);
            }
        }
    });
});

// Global functions for quantity buttons
function incrementModalQuantity() {
    console.log('Incrementing modal quantity');

    // Get current quantity from input
    const quantityInput = document.getElementById('modal-quantity-input');
    if (!quantityInput) {
        console.error('Quantity input not found');
        return;
    }

    let currentQty = parseInt(quantityInput.value) || 1;
    const maxQty = 10; // You can make this dynamic based on stock if needed

    if (currentQty < maxQty) {
        currentQty++;
        quantityInput.value = currentQty;

        // Update Livewire component
        @this.set('quantity', currentQty);

        console.log('Quantity incremented to:', currentQty);
    } else {
        console.log('Quantity already at maximum:', maxQty);
    }
}

function decrementModalQuantity() {
    console.log('Decrementing modal quantity');

    // Get current quantity from input
    const quantityInput = document.getElementById('modal-quantity-input');
    if (!quantityInput) {
        console.error('Quantity input not found');
        return;
    }

    let currentQty = parseInt(quantityInput.value) || 1;

    if (currentQty > 1) {
        currentQty--;
        quantityInput.value = currentQty;

        // Update Livewire component
        @this.set('quantity', currentQty);

        console.log('Quantity decremented to:', currentQty);
    } else {
        console.log('Quantity already at minimum: 1');
    }
}

function updateQuantityFromInput(value) {
    console.log('Quantity input changed to:', value);

    let newQty = parseInt(value) || 1;

    // Validate range
    if (newQty < 1) {
        newQty = 1;
    } else if (newQty > 10) {
        newQty = 10;
    }

    // Update input if value was corrected
    const quantityInput = document.getElementById('modal-quantity-input');
    if (quantityInput) {
        quantityInput.value = newQty;
    }

    // Update Livewire component
    @this.set('quantity', newQty);

    console.log('Quantity updated to:', newQty);
}

function initializeModalQuantity() {
    console.log('Initializing modal quantity');

    const quantityInput = document.getElementById('modal-quantity-input');
    if (quantityInput) {
        // Get current quantity from Livewire
        const currentQty = @this.quantity || 1;
        quantityInput.value = currentQty;
        console.log('Modal quantity initialized to:', currentQty);
    }
}
// --- Keep Alpine slider running after Livewire updates ---
document.addEventListener('DOMContentLoaded', function() {
    Livewire.hook('message.processed', (message, component) => {
        if (component.fingerprint.name === 'product-index') {
            const slider = document.querySelector('[x-ref="slider"]');
            if (slider) {
                // Ensure slider stays a single-row flex
                slider.style.display = 'flex';
                slider.style.flexWrap = 'nowrap';

                // Restart CSS animation by toggling the class
                slider.classList.remove('product-slider');
                // Force reflow
                void slider.offsetWidth;
                slider.classList.add('product-slider');

                // Restore animation duration and play state if Alpine data exists
                const root = slider.closest('[x-data]');
                if (root && window.Alpine && typeof Alpine.initTree === 'function') {
                    try {
                        // Re-initialize Alpine within the slider root so x-data works again
                        Alpine.initTree(root);
                    } catch (e) {
                        console.warn('Alpine initTree failed:', e);
                    }
                }
                // If Alpine state available, set duration/play state from it
                if (root && root.__x) {
                    const scrollSpeed = root.__x.$data?.scrollSpeed || 1;
                    slider.style.animationDuration = (30 / scrollSpeed) + 's';
                    slider.style.animationPlayState = root.__x.$data?.isPaused ? 'paused' : 'running';
                } else {
                    slider.style.animationDuration = '30s';
                    slider.style.animationPlayState = 'running';
                }
            }
        }
    });
});
</script>
