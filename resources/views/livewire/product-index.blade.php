<div x-data="productSlider()" x-init="init()" class="relative overflow-hidden select-none" style="touch-action: pan-y;">
    <!-- Desktop Navigation Buttons -->
    <div class="hidden md:block">
      <button @click="stopLoop(); prev(() => startLoop());"
              class="absolute left-4 top-1/2 z-30 p-3 bg-white/90 rounded-full shadow-lg transition-all duration-200 hover:bg-white hover:scale-110 -translate-y-1/2"
              aria-label="Previous">
        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
      <button @click="stopLoop(); next(true, () => startLoop());"
              class="absolute right-4 top-1/2 z-30 p-3 bg-white/90 rounded-full shadow-lg transition-all duration-200 hover:bg-white hover:scale-110 -translate-y-1/2"
              aria-label="Next">
        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

    <!-- Slider Container -->
    <div class="overflow-hidden select-none" style="-webkit-user-select: none; user-select: none;">
      <div class="flex gap-6 slider-track will-change-transform"
           x-ref="sliderTrack"
           @pointerdown="onDown($event)"
           @pointermove.window="onMove($event)"
           @pointerup.window="onUp($event)"
           @pointercancel.window="onUp($event)"
           :style="`transform: translate3d(${currentX()}px, 0, 0); will-change: transform; cursor: ${isDragging ? 'grabbing' : 'grab'};`"
           tabindex="0" role="region" aria-label="Product Slider">

        @php
          $productCount = max($products->count(), 1);
          $minCards = 8; // ensure there are enough cards to fill wide screens
          $repeatCount = max(1, (int) ceil($minCards / $productCount));
        @endphp
        @for($cycle = 0; $cycle < $repeatCount; $cycle++)
          @foreach($products as $product)
        <div class="slider-card overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md transition hover:shadow-lg"
             data-product-id="{{ $product->id }}"
             data-cycle="{{ $cycle }}">
            <div class="relative overflow-hidden aspect-[4/5] product-image-container"
                 style="cursor: pointer;"
                 onmouseenter="this.querySelector('.main-image').style.opacity='0'; this.querySelector('.gallery-image').style.opacity='1';"
                 onmouseleave="this.querySelector('.main-image').style.opacity='1'; this.querySelector('.gallery-image').style.opacity='0';"
                 onclick="window.location.href='{{ route('product.show', $product->slug) }}'">
                <!-- Badges -->
                <div class="flex absolute left-0 top-2 z-30 flex-col gap-1">
                    @if($this->isBestSeller($product->id))
              <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-green-600 rounded">Best Seller</span>
                    @endif
                    @if($product->compare_price > 0)
              <span class="px-2 py-1 text-xs font-bold text-white uppercase bg-red-600 rounded">Sale</span>
                    @endif
                </div>
                <div class="block relative w-full h-full">
                    @php
                        $mainImage = $product->getFirstMediaUrl('main_image') ?: '/imgs/joyful.png';
                    @endphp
              <img src="{{ $mainImage }}" alt="{{ $product->name }}"
                         class="object-cover w-full h-full transition-opacity duration-500 main-image"
                         style="opacity: 1; transition: opacity 0.5s ease;"
                   width="300" height="300" loading="lazy">
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
              <img src="{{ $galleryImage }}" alt="{{ $product->name }}"
                             class="object-cover absolute top-0 left-0 w-full h-full transition-opacity duration-500 gallery-image"
                             style="opacity: 0; z-index: 2; transition: opacity 0.5s ease;"
                   width="300" height="300" loading="lazy">
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
                     stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
              <span wire:loading wire:target="toggleWishlist({{ $product->id }})"
                    class="absolute top-2 right-2 p-2">
                <svg class="w-5 h-5 text-dark-brown animate-spin"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </div>
                @else
            <a href="{{ route('login') }}" class="absolute top-2 right-2 z-20 p-2 bg-white rounded-full shadow-md transition-colors hover:bg-gray-50"
               title="Login to add to wishlist">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>
                @endauth
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <div>
                <a href="{{ route('product.show', $product->slug) }}" class="text-base font-semibold hover:text-red-600">
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
            </div>
        </div>
        </div>
          @endforeach
        @endfor
            </div>
        </div>

    <!-- Slide Indicators -->
    <div class="flex justify-center gap-2 mt-6">
      <template x-for="(item, index) in totalItems" :key="index">
        <button @click="stopLoop(); goToSlide(index);"
                class="w-2 h-2 rounded-full transition-all duration-300"
                :class="currentIndex === index ? 'bg-gray-800 w-8' : 'bg-gray-300 hover:bg-gray-400'"
                :aria-label="'Go to slide ' + (index + 1)">
                </button>
      </template>
    </div>
  <style>
    .slider-track {
      position: relative;
    }

    .slider-card {
      opacity: 1;
      transform: translateX(0);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .slider-card.slider-card--enter-right {
      opacity: 0;
      transform: translateX(60px);
    }

    .slider-card.slider-card--enter-left {
      opacity: 0;
      transform: translateX(-60px);
    }
  </style>

  <script>
  function productSlider() {
    return {
      // --- Config ---
      cfg: {
        duration: 350,          // ms, animation duration for user interactions
        threshold: 0.15,        // fraction of slide width to change slide when dragging
        throwK: 220,            // inertia multiplier (px/ms * K)
        maxThrowRatio: 0.6,     // max fraction of slide width for inertia throw
        cycleDuration: 45000    // ms to complete one full cycle of all products
      },

      // --- State ---
      currentIndex: 0,
      totalItems: 0,
      itemWidth: 0,
      speed: 0.06,
      isDragging: false,
      startX: 0,
      dragOffset: 0,
      baseX: 0,
      lastX: 0,
      lastT: 0,
      velocity: 0,
      animId: null,
      loopId: null,
      lastFrameTime: null,
      _ro: null,

      // --- Lifecycle ---
      init() {
        this.totalItems = {{ $products->count() }};
        this.recalc();
        this.baseX = 0; // first product flush with left edge

        this._ro = new ResizeObserver(() => {
          this.recalc();
        });
        this._ro.observe(this.$el);

        this.startLoop();
      },

      recalc() {
        const container = this.$refs.sliderTrack;
        if (container && container.children[0]) {
          const cardWidth = container.children[0].offsetWidth;
          this.itemWidth = cardWidth + 24; // 24px gap (from gap-6)
          if (this.totalItems > 0) {
            this.speed = (this.itemWidth * this.totalItems) / this.cfg.cycleDuration;
          }
        }
      },

      // --- Geometry ---
      currentX() {
        if (this.isDragging) {
          return this.baseX + this.dragOffset;
        }
        return this.baseX;
      },

      // --- Continuous Loop ---
      startLoop() {
        this.stopLoop();
        const step = (time) => {
          if (this.lastFrameTime == null) {
            this.lastFrameTime = time;
          }
          const dt = time - this.lastFrameTime;
          this.lastFrameTime = time;

          if (!this.isDragging && !this.animId) {
            this.baseX -= this.speed * dt;
            const track = this.$refs.sliderTrack;
            while (this.baseX <= -this.itemWidth) {
              this.baseX += this.itemWidth;
              if (track && track.firstElementChild) {
                const card = track.firstElementChild;
                track.appendChild(card);
                this.fadeIn(card, 'right');
                this.currentIndex = (this.currentIndex + 1) % this.totalItems;
              }
            }
          }

          this.loopId = requestAnimationFrame(step);
        };
        this.loopId = requestAnimationFrame(step);
      },

      stopLoop() {
        if (this.loopId) cancelAnimationFrame(this.loopId);
        this.loopId = null;
        this.lastFrameTime = null;
      },

      // --- Input ---
      onDown(e) {
        if (e.pointerType === 'mouse' && e.button !== 0) return;
        this.stopLoop();
        this.cancelAnim();
        this.isDragging = true;
        this.startX = e.clientX;
        this.dragOffset = 0;
        this.lastX = e.clientX;
        this.lastT = performance.now();
        this.velocity = 0;
        e.preventDefault();
        e.target.setPointerCapture?.(e.pointerId);
      },

      onMove(e) {
        if (!this.isDragging) return;
        const x = e.clientX;
        this.dragOffset = x - this.startX;
        const now = performance.now();
        const dt = now - this.lastT;
        if (dt > 0) {
          const dx = x - this.lastX;
          const v = dx / dt; // px/ms
          this.velocity = this.velocity * 0.8 + v * 0.2;
          this.lastX = x;
          this.lastT = now;
        }
      },

      onUp() {
        if (!this.isDragging) return;
        this.isDragging = false;

        const MAX_THROW = this.itemWidth * this.cfg.maxThrowRatio;
        let throwDist = this.velocity * this.cfg.throwK;
        throwDist = Math.max(-MAX_THROW, Math.min(MAX_THROW, throwDist));

        const dragDist = this.dragOffset + throwDist;
        if (Math.abs(dragDist) > this.itemWidth * this.cfg.threshold) {
          if (dragDist > 0) {
            this.prev(() => this.startLoop());
          } else {
            this.next(true, () => this.startLoop());
          }
        } else {
          this.animateTo(this.baseX + this.dragOffset, this.baseX, this.cfg.duration, () => {
            this.startLoop();
          });
        }
      },

      // --- Navigation / Animation ---
      goToSlide(i) {
        const steps = ((i - this.currentIndex) % this.totalItems + this.totalItems) % this.totalItems;
        if (steps === 0) return;
        this.stopLoop();
        const stepOnce = (remaining) => {
          this.next(true, () => {
            if (remaining - 1 > 0) {
              stepOnce(remaining - 1);
            } else {
              this.startLoop();
            }
          });
        };
        stepOnce(steps);
      },

      animateTo(from, to, duration, onDone) {
        this.cancelAnim();
        const start = performance.now();
        const easeOutCubic = t => 1 - Math.pow(1 - t, 3);
        const tick = (now) => {
          const t = Math.min(1, (now - start) / duration);
          this.baseX = from + (to - from) * easeOutCubic(t);
          this.dragOffset = 0;
          if (t < 1) this.animId = requestAnimationFrame(tick);
          else {
            this.animId = null;
            this.baseX = to;
            if (onDone) onDone();
          }
        };
        this.animId = requestAnimationFrame(tick);
      },

      cancelAnim() {
        if (this.animId) cancelAnimationFrame(this.animId);
        this.animId = null;
      },

      next(animated = true, cb) {
        const from = this.baseX;
        const to = this.baseX - this.itemWidth;
        const complete = () => {
          const track = this.$refs.sliderTrack;
          if (track && track.firstElementChild) {
            const card = track.firstElementChild;
            track.appendChild(card);
            this.fadeIn(card, 'right');
          }
          this.baseX = 0;
          this.currentIndex = (this.currentIndex + 1) % this.totalItems;
          if (cb) cb();
        };
        if (animated) this.animateTo(from, to, this.cfg.duration, complete);
        else complete();
      },

      prev(cb) {
        const track = this.$refs.sliderTrack;
        if (track && track.lastElementChild) {
          const card = track.lastElementChild;
          track.insertBefore(card, track.firstElementChild);
          this.fadeIn(card, 'left');
        }
        const from = this.baseX - this.itemWidth;
        const to = 0;
        this.baseX = from;
        this.animateTo(from, to, this.cfg.duration, () => {
          this.baseX = 0;
          this.currentIndex = (this.currentIndex - 1 + this.totalItems) % this.totalItems;
          if (cb) cb();
        });
      },

      fadeIn(card, direction = 'right') {
        if (!card) return;
        const enterClass = direction === 'left' ? 'slider-card--enter-left' : 'slider-card--enter-right';
        card.classList.remove('slider-card--enter-left', 'slider-card--enter-right');
        card.classList.add(enterClass);
        // Force reflow so the transition triggers
        void card.offsetWidth;
        requestAnimationFrame(() => {
          card.classList.remove(enterClass);
        });
      }
    };
  }
  </script>
</div>
