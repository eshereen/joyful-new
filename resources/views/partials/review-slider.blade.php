<!--Review Section - Full Screen Slider-->
<section class="relative overflow-hidden" style="height: 100vh;" x-data="{ testimonialActive: 1, totalSlides: 3, autoplayDelay: 5000, intervalId: null, next() { this.testimonialActive = this.testimonialActive >= this.totalSlides ? 1 : this.testimonialActive + 1 }, start() { this.stop(); this.intervalId = setInterval(() => this.next(), this.autoplayDelay) }, stop() { if (this.intervalId) { clearInterval(this.intervalId); this.intervalId = null; } } }" x-init="start()" x-cloak>
    <!-- Slider Content -->
    <div class="relative w-full h-full">
        <!-- Slide 1 -->
        <div x-show="testimonialActive === 1" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full" style="display: none;">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('/imgs/review.jpeg');"></div>
            <div class="absolute inset-0 w-full h-full bg-black/30"></div>

            <!-- Content -->
            <div class="relative z-10 flex items-center justify-center h-full px-4">
                <div class="max-w-4xl mx-auto text-center text-white">
                    <!-- Quote Icon -->
                    <div class="mb-8 text-6xl font-serif text-gray-300 opacity-50">"</div>

                    <!-- Review Text -->
                    <p class="mb-8 text-2xl italic leading-relaxed md:text-3xl">
                        Joyful has completely transformed my shopping experience. The quality is unmatched and the customer service is exceptional!
                    </p>

                    <!-- Customer Name -->
                    <div class="pt-8 border-t border-white border-opacity-30">
                        <h3 class="mb-2 text-2xl font-semibold md:text-3xl">Emily Johnson</h3>
                        <p class="text-lg text-gray-200 md:text-xl">Loyal Customer</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div x-show="testimonialActive === 2" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full" style="display: none;">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('/imgs/bg.jpeg');"></div>
            <div class="absolute inset-0 w-full h-full bg-black/50"></div>

            <!-- Content -->
            <div class="relative z-10 flex items-center justify-center h-full px-4">
                <div class="max-w-4xl mx-auto text-center text-white">
                    <!-- Quote Icon -->
                    <div class="mb-8 text-6xl font-serif text-gray-300 opacity-50">"</div>

                    <!-- Review Text -->
                    <p class="mb-8 text-2xl italic leading-relaxed md:text-3xl">
                        I love the unique collection of products at Joyful. Every item feels special and carefully curated.
                    </p>

                    <!-- Customer Name -->
                    <div class="pt-8 border-t border-white border-opacity-50">
                        <h3 class="mb-2 text-2xl font-semibold md:text-3xl">Michael Chen</h3>
                        <p class="text-lg text-gray-200 md:text-xl">Product Enthusiast</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div x-show="testimonialActive === 3" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full" style="display: none;">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('/imgs/about-1.jpeg');"></div>
            <div class="absolute inset-0 w-full h-full bg-black/50"></div>

            <!-- Content -->
            <div class="relative z-10 flex items-center justify-center h-full px-4">
                <div class="max-w-4xl mx-auto text-center text-white">
                    <!-- Quote Icon -->
                    <div class="mb-8 text-6xl font-serif text-gray-300 opacity-50">"</div>

                    <!-- Review Text -->
                    <p class="mb-8 text-2xl italic leading-relaxed md:text-3xl">
                        The attention to detail in every product is remarkable. Joyful is my go-to for gifts and personal treats.
                    </p>

                    <!-- Customer Name -->
                    <div class="pt-8 border-t border-white border-opacity-30">
                        <h3 class="mb-2 text-2xl font-semibold md:text-3xl">Sophia Rodriguez</h3>
                        <p class="text-lg text-gray-200 md:text-xl">Design Lover</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider Controls - Bottom Center -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
        <div class="flex space-x-3">
            <button @click.prevent="testimonialActive = 1"
                    :class="testimonialActive === 1 ? 'w-12' : 'opacity-50 w-8'"
                    style="background-color: #CBA881;"
                    class="h-3 rounded-full transition-all duration-300 hover:opacity-75 focus:outline-none"></button>
            <button @click.prevent="testimonialActive = 2"
                    :class="testimonialActive === 2 ? 'w-12' : 'opacity-50 w-8'"
                    style="background-color: #CBA881;"
                    class="h-3 rounded-full transition-all duration-300 hover:opacity-75 focus:outline-none"></button>
            <button @click.prevent="testimonialActive = 3"
                    :class="testimonialActive === 3 ? 'w-12' : 'opacity-50 w-8'"
                    style="background-color: #CBA881;"
                    class="h-3 rounded-full transition-all duration-300 hover:opacity-75 focus:outline-none"></button>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button @click.prevent="testimonialActive = testimonialActive === 1 ? 3 : testimonialActive - 1"
            style="background-color: #CBA881;"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 z-20 p-4 text-white rounded-full hover:opacity-80 transition-all duration-300 focus:outline-none">
        <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <button @click.prevent="testimonialActive = testimonialActive >= 3 ? 1 : testimonialActive + 1"
            style="background-color: #CBA881;"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 z-20 p-4 text-white rounded-full hover:opacity-80 transition-all duration-300 focus:outline-none">
        <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
</section>

