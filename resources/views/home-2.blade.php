@extends('layouts.app')
@section('content')

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, #311104 0%, #4C3119 100%);">
        <!-- Background Pattern/Planets -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-20 left-10 w-32 h-32 rounded-full bg-gradient-to-br from-[#CBA881] to-[#4C3119] opacity-40 blur-3xl"></div>
            <div class="absolute top-40 right-20 w-48 h-48 rounded-full bg-gradient-to-br from-white to-[#CBA881] opacity-20 blur-3xl"></div>
            <div class="absolute bottom-32 left-1/3 w-40 h-40 rounded-full bg-gradient-to-br from-[#4C3119] to-[#311104] opacity-30 blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-24 h-24 rounded-full bg-gradient-to-br from-[#CBA881] to-white opacity-25 blur-2xl"></div>

            <!-- Planet-like circles -->
            <svg class="absolute top-10 right-10 w-64 h-64 opacity-10" viewBox="0 0 200 200">
                <circle cx="100" cy="100" r="80" fill="none" stroke="#CBA881" stroke-width="0.5"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="#CBA881" stroke-width="0.5"/>
                <circle cx="100" cy="100" r="40" fill="none" stroke="#CBA881" stroke-width="0.5"/>
            </svg>

            <svg class="absolute bottom-20 left-20 w-48 h-48 opacity-10" viewBox="0 0 200 200">
                <circle cx="100" cy="100" r="70" fill="none" stroke="white" stroke-width="0.5"/>
                <circle cx="100" cy="100" r="50" fill="none" stroke="white" stroke-width="0.5"/>
            </svg>
        </div>

        <!-- Content -->
        <div class="relative z-10 px-6 mx-auto text-center max-w-4xl">
            <!-- Main Heading -->
            <h1 class="mb-6 font-serif text-7xl md:text-8xl lg:text-9xl tracking-tight text-white">
                Joyful
            </h1>

            <!-- Slogan -->
            <p class="mb-12 text-xl md:text-2xl font-light tracking-wide text-[#CBA881] max-w-2xl mx-auto leading-relaxed">
                Illuminate your moments with fragrances that whisper tranquility
            </p>

            <!-- CTA Button -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a
                    href="#"
                    class="group relative inline-flex items-center justify-center px-10 py-4 text-base font-semibold text-white transition-all duration-300 rounded-full overflow-hidden"
                    style="background-color: #CBA881;"
                >
                    <span class="relative z-10 flex items-center gap-2">
                        Shop Now
                        <svg class="w-5 h-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                </a>

                <a
                    href="#"
                    class="group inline-flex items-center justify-center px-10 py-4 text-base font-semibold transition-all duration-300 rounded-full border-2 text-[#CBA881] hover:text-white hover:bg-[#CBA881]"
                    style="border-color: #CBA881;"
                >
                    <span class="flex items-center gap-2">
                        Explore Collection
                    </span>
                </a>
            </div>

            <!-- Subtle Decorative Text -->
            <div class="mt-16 flex items-center justify-center gap-8 text-sm text-[#CBA881] opacity-60">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Hand-Poured
                </span>
                <span class="hidden sm:flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    Natural Ingredients
                </span>
                <span class="hidden md:flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                    </svg>
                    Eco-Friendly
                </span>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-[#CBA881] opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </div>

    <!-- Featured Categories -->
    <section class="px-4 py-16">
        <div class="container mx-auto">
            <h2 class="mb-12 text-3xl font-bold text-center animate-on-scroll">SHOP BY CATEGORY</h2>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <!-- Category 1 -->
                <div class="relative overflow-hidden rounded-lg group animate-on-scroll">
                    <img src="https://picsum.photos/seed/Joyful1/400/500.jpg" alt="Tops" class="object-cover w-full h-full hover-zoom">
                    <div class="absolute inset-0 flex items-center justify-center transition-opacity bg-black opacity-0 bg-opacity-40 group-hover:opacity-100">
                        <a href="#" class="text-xl font-bold text-white">TOPS</a>
                    </div>
                </div>

                <!-- Category 2 -->
                <div class="relative overflow-hidden rounded-lg group animate-on-scroll">
                    <img src="https://picsum.photos/seed/Joyful2/400/500.jpg" alt="Bottoms" class="object-cover w-full h-full hover-zoom">
                    <div class="absolute inset-0 flex items-center justify-center transition-opacity bg-black opacity-0 bg-opacity-40 group-hover:opacity-100">
                        <a href="#" class="text-xl font-bold text-white">BOTTOMS</a>
                    </div>
                </div>

                <!-- Category 3 -->
                <div class="relative overflow-hidden rounded-lg group animate-on-scroll">
                    <img src="https://picsum.photos/seed/Joyful3/400/500.jpg" alt="Accessories" class="object-cover w-full h-full hover-zoom">
                    <div class="absolute inset-0 flex items-center justify-center transition-opacity bg-black opacity-0 bg-opacity-40 group-hover:opacity-100">
                        <a href="#" class="text-xl font-bold text-white">ACCESSORIES</a>
                    </div>
                </div>

                <!-- Category 4 -->
                <div class="relative overflow-hidden rounded-lg group animate-on-scroll">
                    <img src="https://picsum.photos/seed/Joyful4/400/500.jpg" alt="Sale" class="object-cover w-full h-full hover-zoom">
                    <div class="absolute inset-0 flex items-center justify-center transition-opacity bg-black opacity-0 bg-opacity-40 group-hover:opacity-100">
                        <a href="#" class="text-xl font-bold text-white">SALE</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Full-width Lifestyle Banner -->
    <section class="relative overflow-hidden h-96 animate-on-scroll">
        <img src="https://picsum.photos/seed/Joyful-banner1/1920/400.jpg" alt="Lifestyle Banner" class="object-cover w-full h-full">
        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
            <div class="px-4 text-center text-white">
                <h2 class="mb-4 text-3xl font-bold md:text-4xl">SUMMER COLLECTION</h2>
                <p class="max-w-2xl mx-auto mb-6 text-xl">Stay cool and stylish with our latest summer essentials</p>
                <button class="px-8 py-3 font-bold text-white transition-colors bg-red-600 hover:bg-red-700">
                    SHOP NOW
                </button>
            </div>
        </div>
    </section>

    <!-- Product Grid - Women's Collection -->
    <section class="px-4 py-16">
        <div class="container mx-auto">
            <h2 class="mb-4 text-3xl font-bold text-center animate-on-scroll">WOMEN'S COLLECTION</h2>
            <p class="max-w-2xl mx-auto mb-12 text-center text-gray-600 animate-on-scroll">Discover our range of premium activewear designed for performance and style</p>

            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                <!-- Product 1 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/women1/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/women1-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Performance Tank Top</h3>
                    <p class="text-gray-600">$45.00</p>
                </div>

                <!-- Product 2 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/women2/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/women2-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">High-Waist Leggings</h3>
                    <p class="text-gray-600">$65.00</p>
                </div>

                <!-- Product 3 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/women3/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/women3-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Sports Bra</h3>
                    <p class="text-gray-600">$40.00</p>
                </div>

                <!-- Product 4 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/women4/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/women4-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Running Shorts</h3>
                    <p class="text-gray-600">$35.00</p>
                </div>

                <!-- Product 5 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/women5/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/women5-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Hoodie</h3>
                    <p class="text-gray-600">$55.00</p>
                </div>
            </div>

            <div class="mt-12 text-center animate-on-scroll">
                <button class="px-8 py-3 font-bold transition-colors border-2 border-black hover:bg-black hover:text-white">
                    VIEW ALL WOMEN'S
                </button>
            </div>
        </div>
    </section>

    <!-- Three Image Block Section -->
    <section class="px-4 py-16 bg-gray-100">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- RUN Block -->
                <div class="relative overflow-hidden rounded-lg group animate-on-scroll">
                    <img src="https://picsum.photos/seed/run/600/400.jpg" alt="Run" class="object-cover w-full h-full hover-zoom">
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-40">
                        <h3 class="mb-2 text-3xl font-bold text-white">RUN</h3>
                        <p class="px-4 mb-4 text-center text-white">Lightweight gear for your daily runs</p>
                        <a href="#" class="font-medium text-white underline transition-colors hover:text-red-400">SHOP NOW</a>
                    </div>
                </div>

                <!-- TRAIN Block -->
                <div class="relative overflow-hidden rounded-lg group animate-on-scroll">
                    <img src="https://picsum.photos/seed/train/600/400.jpg" alt="Train" class="object-cover w-full h-full hover-zoom">
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-40">
                        <h3 class="mb-2 text-3xl font-bold text-white">TRAIN</h3>
                        <p class="px-4 mb-4 text-center text-white">Durable apparel for intense workouts</p>
                        <a href="#" class="font-medium text-white underline transition-colors hover:text-red-400">SHOP NOW</a>
                    </div>
                </div>

                <!-- REC Block -->
                <div class="relative overflow-hidden rounded-lg group animate-on-scroll">
                    <img src="https://picsum.photos/seed/rec/600/400.jpg" alt="Rec" class="object-cover w-full h-full hover-zoom">
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-40">
                        <h3 class="mb-2 text-3xl font-bold text-white">REC</h3>
                        <p class="px-4 mb-4 text-center text-white">Comfortable styles for recovery days</p>
                        <a href="#" class="font-medium text-white underline transition-colors hover:text-red-400">SHOP NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Grid - Men's Collection -->
    <section class="px-4 py-16">
        <div class="container mx-auto">
            <h2 class="mb-4 text-3xl font-bold text-center animate-on-scroll">MEN'S COLLECTION</h2>
            <p class="max-w-2xl mx-auto mb-12 text-center text-gray-600 animate-on-scroll">Engineered for performance, designed for style</p>

            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                <!-- Product 1 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/men1/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/men1-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Performance T-Shirt</h3>
                    <p class="text-gray-600">$40.00</p>
                </div>

                <!-- Product 2 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/men2/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/men2-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Training Shorts</h3>
                    <p class="text-gray-600">$45.00</p>
                </div>

                <!-- Product 3 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/men3/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/men3-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Hoodie</h3>
                    <p class="text-gray-600">$60.00</p>
                </div>

                <!-- Product 4 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/men4/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/men4-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Joggers</h3>
                    <p class="text-gray-600">$55.00</p>
                </div>

                <!-- Product 5 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/men5/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/men5-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Tank Top</h3>
                    <p class="text-gray-600">$35.00</p>
                </div>
            </div>

            <div class="mt-12 text-center animate-on-scroll">
                <button class="px-8 py-3 font-bold transition-colors border-2 border-black hover:bg-black hover:text-white">
                    VIEW ALL MEN'S
                </button>
            </div>
        </div>
    </section>

    <!-- Full-width Lifestyle Banner 2 -->
    <section class="relative overflow-hidden h-96 animate-on-scroll">
        <img src="https://picsum.photos/seed/Joyful-banner2/1920/400.jpg" alt="Lifestyle Banner" class="object-cover w-full h-full">
        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
            <div class="px-4 text-center text-white">
                <h2 class="mb-4 text-3xl font-bold md:text-4xl">NEW ARRIVALS</h2>
                <p class="max-w-2xl mx-auto mb-6 text-xl">Be the first to shop our latest collection</p>
                <button class="px-8 py-3 font-bold text-white transition-colors bg-red-600 hover:bg-red-700">
                    SHOP NOW
                </button>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="px-4 py-16">
        <div class="container mx-auto">
            <h2 class="mb-4 text-3xl font-bold text-center animate-on-scroll">FEATURED PRODUCTS</h2>
            <p class="max-w-2xl mx-auto mb-12 text-center text-gray-600 animate-on-scroll">Our top picks for the season</p>

            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                <!-- Product 1 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/featured1/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/featured1-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <div class="absolute px-2 py-1 text-xs font-bold text-white bg-red-600 top-4 left-4">NEW</div>
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Ultra Leggings</h3>
                    <p class="text-gray-600">$75.00</p>
                </div>

                <!-- Product 2 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/featured2/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/featured2-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <div class="absolute px-2 py-1 text-xs font-bold text-white bg-red-600 top-4 left-4">BESTSELLER</div>
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Flex Tank</h3>
                    <p class="text-gray-600">$45.00</p>
                </div>

                <!-- Product 3 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/featured3/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/featured3-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <div class="absolute px-2 py-1 text-xs font-bold text-white bg-red-600 top-4 left-4">LIMITED</div>
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Pro Shorts</h3>
                    <p class="text-gray-600">$50.00</p>
                </div>

                <!-- Product 4 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/featured4/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/featured4-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Training Jacket</h3>
                    <p class="text-gray-600">$85.00</p>
                </div>

                <!-- Product 5 -->
                <div class="animate-on-scroll">
                    <div class="relative mb-4 product-image-container">
                        <img src="https://picsum.photos/seed/featured5/300/400.jpg" alt="Product" class="w-full h-auto product-image-primary">
                        <img src="https://picsum.photos/seed/featured5-alt/300/400.jpg" alt="Product Alternate" class="absolute top-0 left-0 w-full h-auto product-image-secondary">
                        <button @click="cartItems++" class="absolute p-2 text-black transition-colors bg-white rounded-full shadow-md bottom-4 right-4 hover:bg-red-600 hover:text-white">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    </div>
                    <h3 class="mb-1 font-medium">Sports Bra</h3>
                    <p class="text-gray-600">$40.00</p>
                </div>
            </div>
        </div>
    </section>

    <!-- App Download Section -->
    <section class="px-4 py-16 text-white bg-black">
        <div class="container mx-auto">
            <div class="flex flex-col items-center justify-between md:flex-row">
                <div class="mb-8 md:mb-0 md:w-1/2 animate-on-scroll">
                    <h2 class="mb-4 text-3xl font-bold">GET THE Joyful APP</h2>
                    <p class="max-w-lg mb-6">Exclusive offers, early access to new collections, and personalized recommendations.</p>
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <button class="flex items-center justify-center px-6 py-3 font-bold text-black transition-colors bg-white hover:bg-gray-200">
                            <i class="mr-2 text-2xl fab fa-apple"></i>
                            <div class="text-left">
                                <div class="text-xs">Download on the</div>
                                <div class="text-sm">App Store</div>
                            </div>
                        </button>
                        <button class="flex items-center justify-center px-6 py-3 font-bold text-black transition-colors bg-white hover:bg-gray-200">
                            <i class="mr-2 text-2xl fab fa-google-play"></i>
                            <div class="text-left">
                                <div class="text-xs">Get it on</div>
                                <div class="text-sm">Google Play</div>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="flex justify-center md:w-1/2 animate-on-scroll">
                    <img src="https://picsum.photos/seed/Joyful-app/300/600.jpg" alt="Joyful App" class="h-auto max-h-80">
                </div>
            </div>
        </div>
    </section>

@endsection
