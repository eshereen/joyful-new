@extends('layouts.app')

@section('content')
<div class="bg-white" x-data="{
    mobileMenuOpen: false,
    scrolled: false,
    testimonialIndex: 0,
    testimonials: [
        {name: 'Emily Johnson', text: 'Joyful has completely transformed my shopping experience. The quality is unmatched and the customer service is exceptional!', rating: 5},
        {name: 'Michael Chen', text: 'I love the unique collection of products at Joyful. Every item feels special and carefully curated.', rating: 5},
        {name: 'Sophia Rodriguez', text: 'The attention to detail in every product is remarkable. Joyful is my go-to for gifts and personal treats.', rating: 5}
    ],
    nextTestimonial() {
        this.testimonialIndex = (this.testimonialIndex + 1) % this.testimonials.length;
    },
    prevTestimonial() {
        this.testimonialIndex = (this.testimonialIndex - 1 + this.testimonials.length) % this.testimonials.length;
    }
}" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50; });">

    <!-- Hero Section -->
    <section class=" realtive h-screen overflow-hidden  top-0 left-0 right-0">
           <!-- Navbar -->

        <div class="absolute inset-0 z-0">
          <img src="{{ asset('imgs/bg.jpeg') }}" alt="Joyful Hero" class="w-full h-full object-cover">
            <div class="video-overlay absolute inset-0"></div>
        </div>

        <div class="relative z-10 h-full flex items-center justify-center text-center text-white px-4">
            <div class="max-w-3xl">
                <h2 class="text-5xl md:text-6xl font-bold mb-6 playfair">Discover Joy in Every Moment</h2>
                <p class="text-xl md:text-2xl mb-8 font-light">Have a joyful time</p>
                <button class="btn-primary bg-yellow-800 text-white px-8 py-4 rounded-full text-lg font-medium">
                    Shop Now
                </button>
            </div>
        </div>
    </section>

    <!-- Bar Section with Background Image -->
    <section class="relative h-48 md:h-56 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center bg-fixed" style="background-image: url('https://picsum.photos/seed/joyfulbar/1920/400.jpg');">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">

            </div>
        </div>
    </section>

    <!-- Products Slider Section -->
    <section class="py-16 overflow-hidden bg-gray-50">
        <div class="container mx-auto px-4">
            <h3 class="text-4xl font-bold text-center mb-12 playfair" style="color: var(--dark-brown);">Featured Products</h3>
        </div>

        <!-- Full-width slider (outside container) -->
        <div class="w-full">
            @livewire('product-index', ['products' => $products])
        </div>

        <div class="container mx-auto px-4">
            <div class="relative" style="display: none;">
                  {{-- Moved slider outside container above --}}
                  {{-- hide old products
                <div class="flex product-slider">
                    <!-- First set of products -->

                    {{-- old products
                    <div class="flex space-x-6 px-3">

                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product1/300/300.jpg" alt="Product 1" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Elegant Watch</h4>
                                <p class="text-gray-600 mb-3">Timeless design meets modern functionality</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>299
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product2/300/300.jpg" alt="Product 2" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Luxury Handbag</h4>
                                <p class="text-gray-600 mb-3">Crafted with premium materials for the discerning</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>599
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product3/300/300.jpg" alt="Product 3" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Designer Sunglasses</h4>
                                <p class="text-gray-600 mb-3">Protect your eyes in style</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>199
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product4/300/300.jpg" alt="Product 4" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Premium Perfume</h4>
                                <p class="text-gray-600 mb-3">A scent that defines your presence</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>149
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product5/300/300.jpg" alt="Product 5" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Silk Scarf</h4>
                                <p class="text-gray-600 mb-3">Elegance you can wear</p>
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
                    <div class="flex space-x-6 px-3">

                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product1/300/300.jpg" alt="Product 1" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Elegant Watch</h4>
                                <p class="text-gray-600 mb-3">Timeless design meets modern functionality</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>299
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product2/300/300.jpg" alt="Product 2" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Luxury Handbag</h4>
                                <p class="text-gray-600 mb-3">Crafted with premium materials for the discerning</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>599
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product3/300/300.jpg" alt="Product 3" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Designer Sunglasses</h4>
                                <p class="text-gray-600 mb-3">Protect your eyes in style</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>199
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product4/300/300.jpg" alt="Product 4" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Premium Perfume</h4>
                                <p class="text-gray-600 mb-3">A scent that defines your presence</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold" style="color: var(--dark-gray-brown);">
                                        <span x-text="currencySymbol"></span>149
                                    </span>
                                    <button class="text-sm font-medium" style="color: var(--gray-orange);">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0">
                            <img src="https://picsum.photos/seed/product5/300/300.jpg" alt="Product 5" class="w-full h-64 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold mb-2">Silk Scarf</h4>
                                <p class="text-gray-600 mb-3">Elegance you can wear</p>
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
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h3 class="text-4xl font-bold text-center mb-12 playfair" style="color: var(--dark-brown);">Our Collections</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="collection-card relative overflow-hidden rounded-lg shadow-lg">
                    <img src="https://picsum.photos/seed/collection1/600/400.jpg" alt="Collection 1" class="w-full h-80 object-cover">
                    <div class="overlay-text absolute inset-0 flex flex-col justify-end p-6 text-white">
                        <h4 class="text-2xl font-bold mb-2">Summer Essentials</h4>
                        <p class="mb-4">Light, breezy, and perfect for those sunny days. Our summer collection combines comfort with effortless style.</p>
                        <button class="btn-secondary w-48 text-center py-2 rounded-full font-medium">
                            See Collection
                        </button>
                    </div>
                </div>
                <div class="collection-card relative overflow-hidden rounded-lg shadow-lg">
                    <img src="https://picsum.photos/seed/collection2/600/400.jpg" alt="Collection 2" class="w-full h-80 object-cover">
                    <div class="overlay-text absolute inset-0 flex flex-col justify-end p-6 text-white">
                        <h4 class="text-2xl font-bold mb-2">Urban Chic</h4>
                        <p class="mb-4">Sophisticated pieces for the modern city dweller. Stand out with our curated urban fashion collection.</p>
                        <button class="btn-secondary w-48 text-center py-2 rounded-full font-medium">
                            See Collection
                        </button>
                    </div>
                </div>
                <div class="collection-card relative overflow-hidden rounded-lg shadow-lg">
                    <img src="https://picsum.photos/seed/collection3/600/400.jpg" alt="Collection 3" class="w-full h-80 object-cover">
                    <div class="overlay-text absolute inset-0 flex flex-col justify-end p-6 text-white">
                        <h4 class="text-2xl font-bold mb-2">Home Comforts</h4>
                        <p class="mb-4">Transform your living space into a sanctuary of comfort and style with our home collection.</p>
                        <button class="btn-secondary w-48 text-center py-2 rounded-full font-medium">
                            See Collection
                        </button>
                    </div>
                </div>
                <div class="collection-card relative overflow-hidden rounded-lg shadow-lg">
                    <img src="https://picsum.photos/seed/collection4/600/400.jpg" alt="Collection 4" class="w-full h-80 object-cover">
                    <div class="overlay-text absolute inset-0 flex flex-col justify-end p-6 text-white">
                        <h4 class="text-2xl font-bold mb-2">Timeless Classics</h4>
                        <p class="mb-4">Pieces that never go out of style. Invest in quality with our collection of timeless classics.</p>
                        <button class="btn-secondary w-48 text-center py-2 rounded-full font-medium">
                            See Collection
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
       <!-- About Section -->
       <section class="py-20 bg-white">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="max-w-4xl mx-auto">
                <img src="{{ asset('imgs/about-1.jpeg') }}" alt="Joyful" class="w-full h-full object-cover rounded-xl">
            </div>
            <div class="max-w-4xl mx-auto text-center">
                <h3 class="text-4xl font-bold mb-8 playfair" style="color: var(--dark-brown);">About Joyful</h3>
                <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                    At Joyful, we believe that every product should bring a moment of happiness to your life. Founded in 2020, our mission is to curate exceptional items that combine quality, style, and functionality. We work with artisans and designers from around the world to bring you unique pieces that tell a story.
                </p>
                <p class="text-lg text-gray-700 mb-8 leading-relaxed">
                    Our commitment to sustainability and ethical practices ensures that every purchase you make not only brings joy to you but also supports communities and protects our planet. Welcome to the Joyful family, where every day is an opportunity to celebrate life's beautiful moments.
                </p>
                <button class="btn-secondary border-2 border-brown-800 px-8 py-3 rounded-full font-medium">
                    Learn More About Us
                </button>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h3 class="text-4xl font-bold text-center mb-12 playfair" style="color: var(--dark-brown);">What Our Customers Say</h3>
            <div class="max-w-3xl mx-auto relative">
                <div class="overflow-hidden">
                    <div class="flex testimonial-slider" :style="`transform: translateX(-${testimonialIndex * 100}%)`">
                        <template x-for="(testimonial, index) in testimonials" :key="index">
                            <div class="testimonial-card px-4">
                                <div class="bg-gray-50 rounded-lg p-8 text-center">
                                    <div class="flex justify-center mb-4">
                                        <template x-for="i in 5" :key="i">
                                            <i class="fas fa-star text-yellow-400"></i>
                                        </template>
                                    </div>
                                    <p class="text-lg text-gray-700 mb-6 italic" x-text="testimonial.text"></p>
                                    <p class="font-semibold" style="color: var(--dark-gray-brown);" x-text="testimonial.name"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <button @click="prevTestimonial()" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md">
                    <i class="fas fa-chevron-left" style="color: var(--dark-gray-brown);"></i>
                </button>
                <button @click="nextTestimonial()" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md">
                    <i class="fas fa-chevron-right" style="color: var(--dark-gray-brown);"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 bg-gray-orange" >
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-4xl font-bold mb-4 text-white playfair">Stay in the Loop</h3>
                <p class="text-lg text-gray-200 mb-8">Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.</p>
                <form class="flex flex-col sm:flex-row gap-4 justify-center">
                    <input type="email" placeholder="Enter your email address" class="newsletter-input px-6 py-3 rounded-full flex-grow max-w-md">
                    <button type="submit" class="btn-primary text-white px-8 py-3 rounded-full font-medium">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
