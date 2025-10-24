   <!-- Header -->


 <header
 x-data="{
   scrolled: false,
  mobileMenuOpen: false,
  categoriesDropdownOpen: false,
  collectionsDropdownOpen: false,
  mobileCategoriesOpen: false,
  mobileCollectionsOpen: false,
   isHome: {{ request()->routeIs('home') ? 'true' : 'false' }},
   init() {
     window.addEventListener('scroll', () => {
       this.scrolled = window.scrollY > 10;
     });
   }
 }"
 :class="{
   'fixed top-0 left-0 right-0 bg-white text-gray-900 shadow-md': (isHome ? scrolled : true),
   'relative bg-transparent text-white': isHome && !scrolled
 }"
 class="z-[1100] transition-all duration-300 py-8 mb-10 font-semibold max-h-44"
>
<div class="container px-8 mx-auto">
  <div class="flex relative justify-between items-center">

      <!-- Mobile Left Side: Cart and Currency -->
      <div class="flex flex-shrink-0 items-center space-x-2 md:hidden">
          <!-- Cart and Wishlist Counts -->
          @livewire('cart-wishlist-counts')
      </div>

      <!-- Desktop Navigation -->
      <nav class="hidden relative flex-1 space-x-4 md:flex">


          <!-- Categories Dropdown -->
          <div class="relative"
               @mouseenter="categoriesDropdownOpen = true"
               @mouseleave="categoriesDropdownOpen = false">
              <button class="flex items-center text-sm font-light transition-colors font-xs hover:text-red-600"
                      :class="isHome && !scrolled ? 'text-white' : 'text-gray-900'">
                  CATEGORIES
                  <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
              </button>

              <!-- Categories Full-Screen Overlay -->
              <div x-show="categoriesDropdownOpen"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 transform -translate-y-2"
                   x-transition:enter-end="opacity-100 transform translate-y-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="opacity-100 transform translate-y-0"
                   x-transition:leave-end="opacity-0 transform -translate-y-2"
                   class="fixed top-0 left-0 right-0 w-full bg-white shadow-lg border-b border-gray-200 z-[1300]"
                   style="display: none;"
                   @click.away="categoriesDropdownOpen = false">

                  <!-- Header -->
                  <div class="py-4 border-b border-white">
                      <div class="container px-4 mx-auto">
                          <div class="flex justify-between items-center">
                              <h2 class="text-2xl font-bold text-gray-900">Browse Categories</h2>
                              <button @click="categoriesDropdownOpen = false"
                                      class="p-2 rounded-full transition-colors hover:bg-white">
                                  <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                  </svg>
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Collections Dropdown -->
          <div class="relative"
               @mouseenter="collectionsDropdownOpen = true"
               @mouseleave="collectionsDropdownOpen = false">
              <button class="flex items-center text-sm transition-colors font-xs hover:text-red-600"
                      :class="isHome && !scrolled ? 'text-white' : 'text-gray-900'">
                  COLLECTIONS
                  <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
              </button>

              <!-- Collections Full-Screen Overlay -->
              <div x-show="collectionsDropdownOpen"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 transform -translate-y-2"
                   x-transition:enter-end="opacity-100 transform translate-y-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="opacity-100 transform translate-y-0"
                   x-transition:leave-end="opacity-0 transform -translate-y-2"
                   class="fixed top-0 left-0 right-0 w-full bg-white shadow-lg border-b border-gray-200 z-[1300]"
                   style="display: none;"
                   @click.away="collectionsDropdownOpen = false">

                  <!-- Header -->
                  <div class="py-4 border-b border-white">
                      <div class="container px-4 mx-auto">
                          <div class="flex justify-between items-center">
                              <h2 class="text-2xl font-bold text-gray-900">Browse Collections</h2>
                              <button @click="collectionsDropdownOpen = false"
                                      class="p-2 rounded-full transition-colors hover:bg-white">
                                  <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                  </svg>
                              </button>
                          </div>
                      </div>
                  </div>

                  <!-- Collections Content -->
                  <div class="container px-4 py-6 mx-auto">
                      <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                          @foreach($collections as $collection)
                          <a href="{{ route('collection.show', $collection->slug) }}"
                             class="block p-4 rounded-lg border border-gray-200 transition-colors hover:border-red-300 hover:bg-gray-50 group"
                             @click="collectionsDropdownOpen = false">
                              <div class="mb-1 text-base font-bold text-gray-900 uppercase transition-colors group-hover:text-red-600">
                                  {{ $collection->name }}
                              </div>
                              <div class="text-xs text-gray-500">
                                  {{ $collection->products_count }} products
                              </div>
                              <div class="mt-2 text-xs font-medium text-red-600 group-hover:text-red-700">
                                  Shop Now →
                              </div>
                          </a>
                          @endforeach
                      </div>

                      <!-- Footer -->
                      <div class="pt-8 mt-12 text-center border-t border-gray-200">
                          <a href="{{ route('collections.index') }}"
                             class="inline-flex items-center px-6 py-3 font-semibold text-white rounded-lg transition-colors bg-gray-950 hover:bg-gray-700"
                             @click="collectionsDropdownOpen = false">
                              View All Collections
                              <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                              </svg>
                          </a>
                      </div>
                  </div>
              </div>
          </div>
      </nav>

          <!-- Logo (Perfectly Centered) -->
      <a href="{{ route('home') }}" class="flex absolute left-1/2 items-center transform -translate-x-1/2 py-4">
          <!-- White logo (home page, not scrolled) -->
          <img x-show="isHome && !scrolled" src="{{asset('/imgs/logo.png')}}" alt="logo" class="w-20 py-2">
          <!-- Black logo (home page scrolled or non-home page) -->
          <img x-show="!isHome || (isHome && scrolled)" src="{{asset('/imgs/logo.png')}}" alt="logo" class="w-20 py-2">
      </a>

      <!-- Desktop Right Side Icons -->
      <div class="hidden md:flex items-center space-x-4 justify-end relative z-[1001] flex-shrink-0">
       <!--Login-->
          <a href="{{ route('login') }}" class="hidden uppercase transition-colors lg:block font-xs hover:text-red-600" :class="isHome && !scrolled ? 'text-white' : 'text-gray-900'"><i class="fas fa-user"></i></a>

           <!-- Cart and Wishlist Counts -->
           @livewire('cart-wishlist-counts')
      </div>

      <!-- Mobile Right Side: Search and Menu -->
      <div class="flex flex-shrink-0 items-center space-x-3 md:hidden">
          <!-- Search Icon -->
          <a href="{{ route('products.search') }}" class="hover:cursor-pointer">
              <i class="text-xl fas fa-search" :class="isHome && !scrolled ? 'text-white' : 'text-gray-950'"></i>
          </a>

          <!-- Mobile Menu Button -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="hover:cursor-pointer" type="button" aria-controls="mobileMenu">
              <i class="text-xl fas fa-bars" :class="isHome && !scrolled ? 'text-white' : 'text-gray-950'"></i>
          </button>
      </div>
  </div>

  <!-- Mobile Menu Overlay -->
  <div x-show="mobileMenuOpen"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-[1200]"
       @click="mobileMenuOpen = false"
       style="display: none;">
  </div>

  <!-- Mobile Menu Sidebar -->
  <div x-show="mobileMenuOpen"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="transform -translate-x-full"
       x-transition:enter-end="transform translate-x-0"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="transform translate-x-0"
       x-transition:leave-end="transform -translate-x-full"
       class="md:hidden fixed top-0 left-0 h-full w-80 bg-white shadow-xl z-[1300] overflow-y-auto"
       style="display: none;">

    <!-- Mobile Menu Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Menu</h2>
        <button @click="mobileMenuOpen = false" class="p-2 rounded-full transition-colors hover:bg-white">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="px-4 py-4 space-y-2">



      <!-- Collections Section -->
      <div class="pt-2 border-t border-gray-200">
        <button @click="mobileCollectionsOpen = !mobileCollectionsOpen"
                class="flex justify-between items-center py-2 w-full font-semibold text-left text-gray-900 transition-colors hover:text-red-600">
          <span>COLLECTIONS</span>
          <svg class="w-4 h-4 transition-transform duration-200"
               :class="mobileCollectionsOpen ? 'rotate-45' : ''"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
        </button>

        <div x-show="mobileCollectionsOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 max-h-0"
             x-transition:enter-end="opacity-100 max-h-96"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 max-h-96"
             x-transition:leave-end="opacity-0 max-h-0"
             class="overflow-hidden"
             style="display: none;">
          <div class="pt-2 pl-4 space-y-1">
            <a href="{{ route('collections.index') }}" class="block py-1 text-sm text-gray-700 transition-colors hover:text-red-600" @click="mobileMenuOpen = false">All Collections</a>
            @foreach($collections as $collection)
            <a href="{{ route('collection.show', $collection->slug) }}" class="block py-1 text-sm text-gray-700 transition-colors hover:text-red-600" @click="mobileMenuOpen = false">{{ $collection->name }}</a>
            @endforeach
          </div>
        </div>
      </div>

      <div class="pt-2 space-y-2 border-t border-gray-200">

        <a href="{{ route('login') }}" class="block py-2 font-semibold text-gray-900 transition-colors hover:text-red-600" @click="mobileMenuOpen = false">ACCOUNT</a>

      </div>
    </nav>
  </div>
</div>
</header>

