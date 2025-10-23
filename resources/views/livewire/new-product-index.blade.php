@if(request()->routeIs('home'))
    <!-- Slider view for home page -->
    @if($products && $products->count() > 0)
    <div class="relative" x-data="{
        isPaused: false,
        init() {
            this.$nextTick(() => {
                this.setupSlider();
            });
        },
        setupSlider() {
            const slider = this.$refs.slider;
            if (slider) {
                slider.style.animationDuration = '30s';
            }
        },
        togglePause() {
            this.isPaused = !this.isPaused;
            const slider = this.$refs.slider;
            if (slider) {
                slider.style.animationPlayState = this.isPaused ? 'paused' : 'running';
            }
        }
    }" x-init="init">
        <!-- Control Buttons -->
        <div class="flex absolute top-4 right-4 z-30 gap-2">
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
        </div>

        <!-- Products Container -->
        <div class="slider-wrapper" wire:ignore>
            <div class="product-slider" x-ref="slider">
                <!-- First set of products -->
                <div class="flex px-3 space-x-6">
                    @foreach($products as $product)
                        <!-- Product card content -->
                    @endforeach
                </div>
                <!-- Duplicate for continuous scroll -->
                <div class="flex px-3 space-x-6">
                    @foreach($products as $product)
                        <!-- Product card content -->
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
@endif