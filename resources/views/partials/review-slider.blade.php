@php
    $backgrounds = [
        ['image' => '/imgs/review.jpeg', 'overlay' => 'bg-black/30'],
        ['image' => '/imgs/bg.jpeg', 'overlay' => 'bg-black/50'],
        ['image' => '/imgs/about-1.jpeg', 'overlay' => 'bg-black/50'],
    ];
    $totalSlides = max($reviews->count(), 1);
@endphp
<!--Review Section - Full Screen Slider-->
<section
    class="relative overflow-hidden"
    style="height: 100vh;"
    x-data="{
        testimonialActive: 1,
        totalSlides: {{ $totalSlides }},
        autoplayDelay: 5000,
        intervalId: null,
        next() {
            this.testimonialActive = this.testimonialActive >= this.totalSlides ? 1 : this.testimonialActive + 1;
        },
        start() {
            this.stop();
            if (this.totalSlides > 1) {
                this.intervalId = setInterval(() => this.next(), this.autoplayDelay);
            }
        },
        stop() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        }
    }"
    x-init="start()"
    x-cloak
>
    <!-- Slider Content -->
    <div class="relative w-full h-full">
        @forelse($reviews as $index => $review)
            @php
                $slideNumber = $index + 1;
                $background = $backgrounds[$index % count($backgrounds)];
            @endphp
            <div
                x-show="testimonialActive === {{ $slideNumber }}"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-700"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 w-full h-full"
                style="display: none;"
            >
                <img src="{{ $background['image'] }}" alt="Customer Review" class="absolute inset-0 w-full h-full object-cover object-center" loading="lazy">
                <div class="absolute inset-0 w-full h-full {{ $background['overlay'] }}"></div>

                <div class="relative z-10 flex items-center justify-center h-full px-4">
                    <div class="max-w-4xl mx-auto text-center text-white">
                        <div class="mb-8 text-6xl font-serif text-gray-300 opacity-50">"</div>

                        <p class="mb-8 text-2xl italic leading-relaxed md:text-3xl">
                            {{ $review->review }}
                        </p>

                        <div class="pt-8 border-t border-white border-opacity-30">
                            <h3 class="mb-2 text-2xl font-semibold md:text-3xl">{{ $review->name }}</h3>
                            <p class="text-lg text-gray-200 md:text-xl">Verified Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                x-show="testimonialActive === 1"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-700"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 w-full h-full"
                style="display: none;"
            >
                <img src="/imgs/review.jpeg" alt="Customer Review" class="absolute inset-0 w-full h-full object-cover object-center" loading="lazy">
                <div class="absolute inset-0 w-full h-full bg-black/30"></div>
                <div class="relative z-10 flex items-center justify-center h-full px-4">
                    <div class="max-w-4xl mx-auto text-center text-white">
                        <div class="mb-8 text-6xl font-serif text-gray-300 opacity-50">"</div>
                        <p class="mb-8 text-2xl italic leading-relaxed md:text-3xl">
                            Joyful has completely transformed my shopping experience. The quality is unmatched and the customer service is exceptional!
                        </p>
                        <div class="pt-8 border-t border-white border-opacity-30">
                            <h3 class="mb-2 text-2xl font-semibold md:text-3xl">Joyful Customer</h3>
                            <p class="text-lg text-gray-200 md:text-xl">Loyal Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Slider Controls - Bottom Center -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
        <div class="flex space-x-3">
            @for($i = 1; $i <= $totalSlides; $i++)
                <button
                    @click.prevent="testimonialActive = {{ $i }}"
                    :class="testimonialActive === {{ $i }} ? 'w-12' : 'opacity-50 w-8'"
                    style="background-color: #CBA881;"
                    class="h-3 rounded-full transition-all duration-300 hover:opacity-75 focus:outline-none"
                ></button>
            @endfor
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button
        @click.prevent="testimonialActive = testimonialActive === 1 ? totalSlides : testimonialActive - 1"
            style="background-color: #CBA881;"
        class=" hidden lg:block absolute left-4 top-1/2 transform -translate-y-1/2 z-20 p-4 text-white rounded-full hover:opacity-80 transition-all duration-300 focus:outline-none"
        x-show="totalSlides > 1"
    >
        <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <button
        @click.prevent="testimonialActive = testimonialActive >= totalSlides ? 1 : testimonialActive + 1"
            style="background-color: #CBA881;"
        class=" hidden lg:block absolute right-4 top-1/2 transform -translate-y-1/2 z-20 p-4 text-white rounded-full hover:opacity-80 transition-all duration-300 focus:outline-none"
        x-show="totalSlides > 1"
    >
        <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
</section>

