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
    <section class=" realtive h-screen overflow-hidden  top-0 left-0 right-0">
           <!-- Navbar -->

        <div class="absolute inset-0 z-0">
          <img src="{{ asset('imgs/bg.jpeg') }}" alt="Joyful Hero" class="w-full h-full object-cover">
            <div class="video-overlay absolute inset-0 bg-black/40"></div>
        </div>

        <div class="relative z-10 h-full flex items-center justify-center text-center text-white px-4">
            <div class="max-w-3xl">
                <h2 class="text-5xl md:text-6xl font-bold mb-6 playfair">Discover Joy in Every Moment</h2>
                <p class="text-xl md:text-2xl mb-8 font-light">Have a joyful time</p>
                <button class="btn-primary bg-yellow-900 text-white px-8 py-4 rounded-full text-lg font-medium">
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
            @if($collections->count() >0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($collections as $collection)
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
                @endforeach
             
            </div>
            @endif
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

<!--Review-->
	<div class="my-10 md:my-24 container mx-auto flex flex-col md:flex-row shadow-sm overflow-hidden" x-data="{ testimonialActive: 2 }" x-cloak>
		

			<div class="bg-gray-100 md:w-1/2">
				<div class="flex flex-col h-full relative">

					<div class="absolute top-0 left-0 mt-3 ml-4 md:mt-5 md:ml-12">
						<svg xmlns="http://www.w3.org/2000/svg" class="text-gray-200 fill-current w-12 h-12 md:w-16 md:h-16" viewBox="0 0 24 24"><path d="M6.5 10c-.223 0-.437.034-.65.065.069-.232.14-.468.254-.68.114-.308.292-.575.469-.844.148-.291.409-.488.601-.737.201-.242.475-.403.692-.604.213-.21.492-.315.714-.463.232-.133.434-.28.65-.35.208-.086.39-.16.539-.222.302-.125.474-.197.474-.197L9.758 4.03c0 0-.218.052-.597.144C8.97 4.222 8.737 4.278 8.472 4.345c-.271.05-.56.187-.882.312C7.272 4.799 6.904 4.895 6.562 5.123c-.344.218-.741.4-1.091.692C5.132 6.116 4.723 6.377 4.421 6.76c-.33.358-.656.734-.909 1.162C3.219 8.33 3.02 8.778 2.81 9.221c-.19.443-.343.896-.468 1.336-.237.882-.343 1.72-.384 2.437-.034.718-.014 1.315.028 1.747.015.204.043.402.063.539.017.109.025.168.025.168l.026-.006C2.535 17.474 4.338 19 6.5 19c2.485 0 4.5-2.015 4.5-4.5S8.985 10 6.5 10zM17.5 10c-.223 0-.437.034-.65.065.069-.232.14-.468.254-.68.114-.308.292-.575.469-.844.148-.291.409-.488.601-.737.201-.242.475-.403.692-.604.213-.21.492-.315.714-.463.232-.133.434-.28.65-.35.208-.086.39-.16.539-.222.302-.125.474-.197.474-.197L20.758 4.03c0 0-.218.052-.597.144-.191.048-.424.104-.689.171-.271.05-.56.187-.882.312-.317.143-.686.238-1.028.467-.344.218-.741.4-1.091.692-.339.301-.748.562-1.05.944-.33.358-.656.734-.909 1.162C14.219 8.33 14.02 8.778 13.81 9.221c-.19.443-.343.896-.468 1.336-.237.882-.343 1.72-.384 2.437-.034.718-.014 1.315.028 1.747.015.204.043.402.063.539.017.109.025.168.025.168l.026-.006C13.535 17.474 15.338 19 17.5 19c2.485 0 4.5-2.015 4.5-4.5S19.985 10 17.5 10z"/></svg>
					</div>
					 
					<div class="h-full relative z-10">
						<div x-show.immediate="testimonialActive === 1">
							<p class="text-gray-600 serif font-normal italic px-6 py-6 md:px-16 md:py-10 text-xl md:text-2xl" x-show.transition="testimonialActive == 1">
								Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.
							</p>
						</div>
						
						<div x-show.immediate="testimonialActive === 2">
							<p class="text-gray-600 serif font-normal italic px-6 py-6 md:px-16 md:py-10 text-xl md:text-2xl" x-show.transition="testimonialActive == 2">
								Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
							</p>
						</div>
						
						<div x-show.immediate="testimonialActive === 3"> 
							<p class="text-gray-600 serif font-normal italic px-6 py-6 md:px-16 md:py-10 text-xl md:text-2xl" x-show.transition="testimonialActive == 3">
								Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.
							</p>
						</div>
					</div>

					<div class="flex my-4 justify-center items-center">
						<button 
							@click.prevent="testimonialActive = 1" 
							class="text-center font-bold shadow-xs focus:outline-none focus:shadow-outline inline-block rounded-full mx-2"
							:class="{'h-12 w-12 opacity-25 bg-yellow-800 text-gray-100': testimonialActive != 1, 'h-16 w-16 opacity-100 bg-yellow-800 text-white': testimonialActive == 1 }"	
						>JD</button>
						<button 
							@click.prevent="testimonialActive = 2" 
							class="text-center font-bold shadow-xs focus:outline-none focus:shadow-outline h-16 w-16 inline-block bg-yellow-800 rounded-full mx-2"
							:class="{'h-12 w-12 opacity-25 bg-yellow-800 text-gray-100': testimonialActive != 2, 'h-16 w-16 opacity-100 bg-yellow-800 text-white': testimonialActive == 2 }"	
						>WD</button>
						<button 
							@click.prevent="testimonialActive = 3" 
							class="text-center font-bold shadow-xs focus:outline-none focus:shadow-outline h-12 w-12 inline-block bg-yellow-800 rounded-full mx-2"
							:class="{'h-12 w-12 opacity-25 bg-yellow-800 text-gray-100': testimonialActive != 3, 'h-16 w-16 opacity-100 bg-yellow-800 text-white': testimonialActive == 3 }"
						>JW</button>
					</div>
					 
					<div class="flex justify-center px-6 pt-2 pb-6 md:py-6">
						<div class="text-center" x-show="testimonialActive == 1">
							<h2 class="text-sm md:text-base font-bold text-gray-700 leading-tight">John Doe</h2>
							<small class="text-gray-500 text-xs md:text-sm truncate">CEO, ABC Inc.</small>
						</div>

						<div class="text-center" x-show="testimonialActive == 2">
							<h2 class="text-sm md:text-base font-bold text-gray-700 leading-tight">Winter Doe</h2>
							<small class="text-gray-500 text-xs md:text-sm truncate">CTO, XYZ Corp.</small>
						</div>

						<div class="text-center" x-show="testimonialActive == 3">
							<h2 class="text-sm md:text-base font-bold text-gray-700 leading-tight">John Wick</h2>
							<small class="text-gray-500 text-xs md:text-sm truncate">Product Manager, Fake Corp.</small>
						</div>	 
					</div>
				</div>
			</div>
            	<div class="relative w-full py-2 md:py-24  md:w-1/2 flex flex-col item-center justify-center" style = "background-image:url('/imgs/review.jpeg');background-position:cover; background-repeate:no-repeate">
				
				<div class="absolute top-0 left-0 z-10 grid-indigo w-16 h-16 md:w-40 md:h-40 md:ml-20 md:mt-24"></div>
				
				<div class="relative text-2xl md:text-5xl py-2 px-6 md:py-6 md:px-1 md:w-64 md:mx-auto text-gray-100 font-semibold leading-tight tracking-tight mb-0 z-20">
					<span class="md:block">What Our</span>
					<span class="md-block">Customers</span>
					<span class="block">Are Saying!</span>
				</div>

				<div class="absolute right-0 bottom-0 mr-4 mb-4 hidden md:block">
					<button 
						class="rounded-l-full border-r bg-gray-100 text-gray-500 focus:outline-none hover:text-yellow-900 font-bold w-12 h-10"
						x-on:click="testimonialActive = testimonialActive === 1 ? 3 : testimonialActive - 1">
						&#8592;
					</button><button 
						class="rounded-r-full bg-gray-100 text-gray-500 focus:outline-none hover:text-yellow-900 font-bold w-12 h-10"
						x-on:click="testimonialActive = testimonialActive >= 3 ? 1 : testimonialActive + 1">
						&#8594;
					  </button>
				</div>
			</div>
		</div>

    <!-- Newsletter Section -->
    <section class="py-20 bg-gray-orange" >
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-4xl font-bold mb-4 text-white playfair">Stay in the Loop</h3>
                <p class="text-lg text-gray-200 mb-8">Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.</p>
              <div class="flex justify-center items-center">
                <livewire:newsletter.subscribe-form />
              </div>
               
            </div>
        </div>
    </section>
</div>
@endsection
