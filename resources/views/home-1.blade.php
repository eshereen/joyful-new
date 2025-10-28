@extends('layouts.app')

@section('content')
<div class="bg-white" x-data="{
    mobileMenuOpen: false,
    scrolled: false,
    testimonials: [
        {name: 'Emily Johnson', text: 'Joyful has completely transformed my shopping experience. The quality is unmatched and the customer service is exceptional!', rating: 5},
        {name: 'Michael Chen', text: 'I love the unique collection of products at Joyful. Every item feels special and carefully curated.', rating: 5},
        {name: 'Sophia Rodriguez', text: 'The attention to detail in every product is remarkable. Joyful is my go-to for gifts and personal treats.', rating: 5}
    ],
    isPaused: false,
    init() {
        this.$nextTick(() => {
            const slider = this.$refs.reviewSlider;
            if (slider) {
                slider.style.animationDuration = '25s';
                slider.style.animationPlayState = this.isPaused ? 'paused' : 'running';
            }
        });
    },
    togglePause() {
        this.isPaused = !this.isPaused;
        const slider = this.$refs.reviewSlider;
        if (slider) {
            slider.style.animationPlayState = this.isPaused ? 'paused' : 'running';
        }
    }
}" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50; });">

    <!-- Hero Section -->
    <section class="overflow-hidden top-0 right-0 left-0 h-screen  realtive">
           <!-- Navbar -->

        <div class="absolute inset-0 z-0">
          <img src="{{ asset('imgs/bg.jpeg') }}" alt="Joyful Hero" class="object-cover w-full h-full" loading="eager" fetchpriority="high">
            <div class="absolute inset-0 video-overlay bg-black/40"></div>
        </div>

        <div class="flex relative z-10 justify-center items-center px-4 h-full text-center text-white">
            <div class="max-w-3xl">
                <h1 id="hero-title" class="text-5xl md:text-6xl lg:text-[120px] font-something mb-6">Bring joy to your space</h1>
                <p class="mb-8 text-xl font-light md:text-2xl">Have a joyful time</p>
                <button class="px-8 py-4 text-lg font-medium text-white rounded-full btn-primary bg-dark-brown">
                    Shop Now
                </button>
            </div>
        </div>
    </section>

    <!-- Bar Section with Background Image -->
    <section class="overflow-hidden relative h-48 md:h-56">
        <div class="absolute inset-0 bg-fixed bg-center bg-cover" style="background-image: url('/imgs/background.png');">
            <div class="flex absolute inset-0 justify-center items-center bg-black/20">

            </div>
        </div>
    </section>

    <!-- Products Slider Section -->
    <section class="overflow-hidden py-16 bg-gray-50">
        <div class="container px-4 mx-auto">
            <h3 class="mb-12 text-4xl font-bold text-center playfair" style="color: var(--dark-brown);">Featured Products</h3>
        </div>

        <!-- Full-width slider (outside container) -->
        <div class="w-full">
            @livewire('product-index', ['products' => $products])
        </div>

        <div class="container px-4 mx-auto">
            <div class="relative" style="display: none;">
                  {{-- Moved slider outside container above --}}
                  {{-- hide old products
                <div class="flex product-slider">
                    <!-- First set of products -->

                    {{-- old products
                    <div class="flex px-3 space-x-6">

                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product1/300/300.jpg" alt="Product 1" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Elegant Watch</h4>
                                <p class="mb-3 text-gray-600">Timeless design meets modern functionality</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>299
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product2/300/300.jpg" alt="Product 2" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Luxury Handbag</h4>
                                <p class="mb-3 text-gray-600">Crafted with premium materials for the discerning</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>599
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product3/300/300.jpg" alt="Product 3" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Designer Sunglasses</h4>
                                <p class="mb-3 text-gray-600">Protect your eyes in style</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>199
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product4/300/300.jpg" alt="Product 4" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Premium Perfume</h4>
                                <p class="mb-3 text-gray-600">A scent that defines your presence</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>149
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product5/300/300.jpg" alt="Product 5" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Silk Scarf</h4>
                                <p class="mb-3 text-gray-600">Elegance you can wear</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>89
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Duplicate set for continuous scrolling -->
                    <div class="flex px-3 space-x-6">

                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product1/300/300.jpg" alt="Product 1" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Elegant Watch</h4>
                                <p class="mb-3 text-gray-600">Timeless design meets modern functionality</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>299
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product2/300/300.jpg" alt="Product 2" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Luxury Handbag</h4>
                                <p class="mb-3 text-gray-600">Crafted with premium materials for the discerning</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>599
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product3/300/300.jpg" alt="Product 3" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Designer Sunglasses</h4>
                                <p class="mb-3 text-gray-600">Protect your eyes in style</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>199
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product4/300/300.jpg" alt="Product 4" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Premium Perfume</h4>
                                <p class="mb-3 text-gray-600">A scent that defines your presence</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>149
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-shrink-0 w-64 bg-white rounded-lg shadow-md">
                            <img src="https://picsum.photos/seed/product5/300/300.jpg" alt="Product 5" class="object-cover w-full h-64">
                            <div class="p-4">
                                <h4 class="mb-2 text-lg font-semibold">Silk Scarf</h4>
                                <p class="mb-3 text-gray-600">Elegance you can wear</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>89
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        --}}
    </div>
        </div>
    </section>
    <!-- Collections Section -->
    @if($collections->count() >0)
    <section class="py-20 bg-gray-50">
        <div class="container px-4 mx-auto">
            <h3 class="mb-12 text-4xl font-bold text-center playfair" style="color: var(--dark-brown);">Our Collections</h3>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                @foreach($collections as $collection)
                <div class="overflow-hidden relative rounded-lg shadow-lg collection-card">
                    <img src="{{ $collection->getFirstMediaUrl('main_image') }}" alt="{{ $collection->name }}" class="object-cover w-full h-80" loading="lazy">
                    <div class="flex absolute inset-0 flex-col justify-end p-6 text-white overlay-text">
                        <h4 class="mb-2 text-2xl font-bold">{{ $collection->name }}</h4>
                        <p class="mb-4">{{ $collection->description }}</p>
                        <button class="py-2 w-48 font-medium text-center rounded-full btn-secondary">
                            See Collection
                        </button>
                    </div>
                </div>
                @endforeach

            </div>

        </div>
    </section>
    @endif
       <!-- About Section -->
       <section class="py-20 bg-white">
        <div class="container grid grid-cols-1 gap-4 px-4 mx-auto md:grid-cols-2">
            <div class="mx-auto max-w-4xl">
                <img src="{{ asset('imgs/about-1.jpeg') }}" alt="Joyful" class="object-cover w-full h-full rounded-xl" loading="lazy">
            </div>
            <div class="mx-auto max-w-4xl text-center">
                <h3 class="mb-8 text-4xl font-bold playfair" style="color: var(--dark-brown);">About Joyful</h3>
                <p class="mb-6 text-lg leading-relaxed text-gray-700">
                    At Joyful, we believe that every product should bring a moment of happiness to your life. Founded in 2020, our mission is to curate exceptional items that combine quality, style, and functionality. We work with artisans and designers from around the world to bring you unique pieces that tell a story.
                </p>
                <p class="mb-8 text-lg leading-relaxed text-gray-700">
                    Our commitment to sustainability and ethical practices ensures that every purchase you make not only brings joy to you but also supports communities and protects our planet. Welcome to the Joyful family, where every day is an opportunity to celebrate life's beautiful moments.
                </p>
                <button class="px-8 py-3 font-medium rounded-full border-2 btn-secondary border-brown-800">
                    Learn More About Us
                </button>
            </div>
        </div>
    </section>

<!--Review-->
<section class="py-20 bg-white">
@include('partials.review-slider')
        </section>
<!-- Contact Section -->
<section class="overflow-hidden relative min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50">
    <!-- Decorative Background Elements -->
    <div class="overflow-hidden absolute inset-0">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-200 rounded-full opacity-30 mix-blend-multiply filter blur-xl animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-yellow-200 rounded-full opacity-30 mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
      <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-amber-200 rounded-full opacity-20 mix-blend-multiply filter blur-xl animate-pulse transform -translate-x-1/2 -translate-y-1/2 animation-delay-4000"></div>
    </div>

    <!-- Floating Candle Icons -->
    <div class="absolute left-10 top-20 text-orange-300 opacity-20 animate-bounce animation-delay-1000">
      <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2C11.5 2 10 4 10 6C10 8 11.5 10 12 10C12.5 10 14 8 14 6C14 4 12.5 2 12 2M12 12C10.9 12 10 12.9 10 14V20C10 21.1 10.9 22 12 22C13.1 22 14 21.1 14 20V14C14 12.9 13.1 12 12 12Z"/>
      </svg>
    </div>
    <div class="absolute right-10 bottom-20 text-yellow-300 opacity-20 animate-bounce animation-delay-3000">
      <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2C11.5 2 10 4 10 6C10 8 11.5 10 12 10C12.5 10 14 8 14 6C14 4 12.5 2 12 2M12 12C10.9 12 10 12.9 10 14V20C10 21.1 10.9 22 12 22C13.1 22 14 21.1 14 20V14C14 12.9 13.1 12 12 12Z"/>
      </svg>
    </div>

    <div class="container relative px-4 py-20 mx-auto">
      <div class="mx-auto max-w-4xl">
        <!-- Header Section -->
        <div class="mb-16 text-center">
          <div class="inline-flex justify-center items-center mb-6">
            <div class="relative">
              <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-yellow-400 rounded-full opacity-50 blur-lg animate-pulse"></div>
              <div class="relative p-4 bg-white rounded-full shadow-lg">
                <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C11.5 2 10 4 10 6C10 8 11.5 10 12 10C12.5 10 14 8 14 6C14 4 12.5 2 12 2M12 12C10.9 12 10 12.9 10 14V20C10 21.1 10.9 22 12 22C13.1 22 14 21.1 14 20V14C14 12.9 13.1 12 12 12Z"/>
                </svg>
              </div>
            </div>
          </div>

          <h1 class="mb-6 text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600 md:text-5xl animate-fade-in">
            Ignite Your Senses
          </h1>

          <p class="mx-auto max-w-2xl text-xl leading-relaxed text-gray-700">
            Let the warm glow of connection illuminate your journey. Share your thoughts, questions, or custom candle dreams with us. We're here to craft aromatic experiences that resonate with your soul.
          </p>
        </div>

        <!-- Contact Form Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-8 md:p-12 transform hover:scale-[1.02] transition-transform duration-500">
          <div class="grid gap-8 md:grid-cols-2">
            <!-- Left Side - Visual Element -->
            <div class="hidden flex-col justify-center items-center md:flex">
              <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-yellow-400 rounded-full opacity-30 blur-2xl animate-pulse"></div>
                <img src="{{ asset('imgs/contact.jpeg') }}" alt="Scented Candle" class="relative mx-auto w-full max-w-sm rounded-2xl shadow-xl" loading="lazy">
              </div>
              <div class="mt-8 text-center">
                <p class="italic text-gray-600">"Where every flame tells a story"</p>
                <div class="flex justify-center mt-4 space-x-1">
                  <span class="inline-block w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                  <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full animate-pulse animation-delay-200"></span>
                  <span class="inline-block w-2 h-2 bg-amber-400 rounded-full animate-pulse animation-delay-400"></span>
                </div>
              </div>
            </div>

            <!-- Right Side - Form -->
            <div class="space-y-6">
              <form id="contactForm" class="space-y-6">
                <!-- Name Field -->
                <div class="group">
                  <label for="name" class="block mb-2 text-sm font-semibold text-gray-700 transition-colors group-focus-within:text-orange-600">
                    Your Name
                  </label>
                  <div class="relative">
                    <input
                      type="text"
                      id="name"
                      name="name"
                      required
                      class="px-4 py-3 pl-12 w-full bg-gray-50 rounded-xl border-2 border-gray-200 transition-all duration-300 focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-orange-100"
                      placeholder="Enter your name"
                    >
                    <svg class="absolute left-4 top-1/2 w-5 h-5 text-gray-400 transition-colors transform -translate-y-1/2 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                </div>

                <!-- Email Field -->
                <div class="group">
                  <label for="email" class="block mb-2 text-sm font-semibold text-gray-700 transition-colors group-focus-within:text-orange-600">
                    Email Address
                  </label>
                  <div class="relative">
                    <input
                      type="email"
                      id="email"
                      name="email"
                      required
                      class="px-4 py-3 pl-12 w-full bg-gray-50 rounded-xl border-2 border-gray-200 transition-all duration-300 focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-orange-100"
                      placeholder="your@email.com"
                    >
                    <svg class="absolute left-4 top-1/2 w-5 h-5 text-gray-400 transition-colors transform -translate-y-1/2 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                </div>

                <!-- Phone Field -->
                <div class="group">
                  <label for="phone" class="block mb-2 text-sm font-semibold text-gray-700 transition-colors group-focus-within:text-orange-600">
                    Phone Number
                  </label>
                  <div class="relative">
                    <input
                      type="tel"
                      id="phone"
                      name="phone"
                      required
                      class="px-4 py-3 pl-12 w-full bg-gray-50 rounded-xl border-2 border-gray-200 transition-all duration-300 focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-orange-100"
                      placeholder="+1 (555) 123-4567"
                    >
                    <svg class="absolute left-4 top-1/2 w-5 h-5 text-gray-400 transition-colors transform -translate-y-1/2 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                  </div>
                </div>

                <!-- Message Field -->
                <div class="group">
                  <label for="message" class="block mb-2 text-sm font-semibold text-gray-700 transition-colors group-focus-within:text-orange-600">
                    Your Message
                  </label>
                  <div class="relative">
                    <textarea
                      id="message"
                      name="message"
                      rows="4"
                      required
                      class="px-4 py-3 pl-12 w-full bg-gray-50 rounded-xl border-2 border-gray-200 transition-all duration-300 resize-none focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-orange-100"
                      placeholder="Share your thoughts, custom requests, or questions..."
                    ></textarea>
                    <svg class="absolute top-4 left-4 w-5 h-5 text-gray-400 transition-colors group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                  </div>
                </div>

                <!-- Submit Button -->
                <button
                  type="submit"
                  class="overflow-hidden relative px-8 py-4 w-full font-semibold text-white bg-gradient-to-r from-orange-500 to-yellow-500 rounded-xl shadow-lg transition-all duration-300 transform group hover:shadow-2xl hover:-translate-y-1"
                >
                  <span class="flex relative z-10 justify-center items-center">
                    <svg class="mr-2 w-5 h-5 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Send Your Message
                  </span>
                  <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-yellow-600 transition-transform duration-300 transform translate-y-full group-hover:translate-y-0"></div>
                </button>
              </form>

              <!-- Success Message (Hidden by default) -->
              <div id="successMessage" class="hidden p-4 text-center bg-green-50 rounded-xl border-2 border-green-200">
                <div class="flex justify-center items-center text-green-600">
                  <svg class="mr-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span class="font-medium">Thank you! Your message has been sent successfully.</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-16 text-center">
          <p class="mb-4 text-gray-600">Or reach us directly at</p>
          <div class="flex flex-wrap gap-6 justify-center">
            <a href="mailto:info@joyfulegy.com" class="inline-flex items-center text-orange-600 transition-colors hover:text-dark-brown">
              <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              info@joyfulegy.com
            </a>

          </div>
        </div>
      </div>
    </div>

    <style>
      @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
      }
      .animate-fade-in {
        animation: fade-in 1s ease-out;
      }
      .animation-delay-2000 { animation-delay: 2s; }
      .animation-delay-4000 { animation-delay: 4s; }
      .animation-delay-1000 { animation-delay: 1s; }
      .animation-delay-3000 { animation-delay: 3s; }
      .animation-delay-200 { animation-delay: 200ms; }
      .animation-delay-400 { animation-delay: 400ms; }
    </style>

    <script>
      document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show success message
        const successMessage = document.getElementById('successMessage');
        successMessage.classList.remove('hidden');

        // Reset form
        this.reset();

        // Hide success message after 5 seconds
        setTimeout(() => {
          successMessage.classList.add('hidden');
        }, 5000);
      });
    </script>
  </section>


        <!-- Newsletter Section -->
    <section class="py-40 bg-fixed bg-center bg-cover bg-gray-orange" style="background-image:url('/imgs/review.jpeg');background-position:cover; background-repeate:no-repeate" >
        <div class="container px-4 mx-auto">
            <div class="mx-auto max-w-2xl text-center">
                <h3 class="mb-4 text-4xl font-bold text-white playfair">Stay in the Loop</h3>
                <p class="mb-8 text-lg text-gray-200">Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.</p>
              <div class="flex justify-center items-center">
                <livewire:newsletter.subscribe-form />
              </div>

            </div>
        </div>
    </section>
</div>
@endsection
