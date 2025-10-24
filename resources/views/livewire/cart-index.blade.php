<div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-3xl font-bold mb-8">Your Shopping Cart</h1>


    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Cart Items -->
        <div class="lg:w-2/3">
            <div class="overflow-x-auto shadow-sm border border-gray-200 rounded-lg">
                @if($cartCount > 0)
                    <table class="w-full min-w-[600px]">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr class="text-left text-sm text-gray-500">
                                <th class="pb-4 pt-4 px-4 font-medium min-w-[200px]">Product</th>
                                <th class="pb-4 pt-4 px-4 font-medium min-w-[120px]">Quantity</th>
                                <th class="pb-4 pt-4 px-4 font-medium min-w-[100px]">Total</th>
                                <th class="pb-4 pt-4 px-4 font-medium min-w-[60px]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr class="border-b border-white hover:bg-gray-50 transition-colors">
                                    <td class="py-6 px-4">
                                        <div class="flex items-center space-x-4">
                                            @if(isset($item['attributes']['image']) && $item['attributes']['image'])
                                                <img src="{{ $item['attributes']['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            @endif
                                            <div class="min-w-0 flex-1">
                                                <h3 class="font-medium text-gray-900 truncate">{{ $item['name'] }}</h3>
                                                @if(isset($item['attributes']['size']) || isset($item['attributes']['wick_type']))
                                                    <div class="text-sm text-gray-500 mt-1 flex flex-wrap gap-1">
                                                        @if(isset($item['attributes']['size']))
                                                            <span class="inline-block bg-white px-2 py-1 rounded text-xs">
                                                                Size: {{ $item['attributes']['size'] }}gm
                                                            </span>
                                                        @endif
                                                        @if(isset($item['attributes']['wick_type']))
                                                            <span class="inline-block bg-white px-2 py-1 rounded text-xs">
                                                                Wick: {{ $item['attributes']['wick_type'] }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-6 px-4">
                                        <div class="flex border rounded-md w-24 mx-auto">
                                            <button
                                                wire:click="decreaseQuantity('{{ $item['rowId'] }}')"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="px-2 py-1 text-gray-600 hover:bg-white {{ $item['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                                title="Decrease quantity"
                                            >-</button>
                                            <span class="px-2 py-1 border-x text-center">
                                                <span wire:loading.remove>{{ $item['quantity'] }}</span>
                                                <span wire:loading class="text-gray-400">...</span>
                                            </span>
                                            <button
                                                wire:click="increaseQuantity('{{ $item['rowId'] }}')"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="px-2 py-1 text-gray-600 hover:bg-white"
                                                title="Increase quantity"
                                            >+</button>
                                        </div>
                                    </td>
                                    <td class="py-6 px-4 text-center">
                                        <span class="font-medium">{{ number_format($item['price'] * $item['quantity'], 2) }} EGP</span>
                                    </td>
                                    <td class="py-6 px-4 text-center">
                                        <button
                                            wire:click="removeItem('{{ $item['rowId'] }}')"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50 cursor-not-allowed"
                                            class="text-gray-400 hover:text-pink-500 transition-colors"
                                            title="Remove {{ $item['name'] }} from cart"
                                        >
                                            <span wire:loading.remove>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </span>
                                            <span wire:loading>
                                                <svg class="animate-spin h-5 w-5 text-pink-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <h2 class="text-2xl font-semibold mb-4">Your cart is empty</h2>
                        <p class="mb-6">Looks like you haven't added any items to your cart yet.</p>
                        <a href="{{ route('home') }}"
                           class="bg-red-600 text-white py-2 px-6 rounded-lg inline-block hover:bg-red-700 transition">
                            Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:w-1/3">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h2 class="text-xl font-bold mb-6">Order Summary</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>{{ number_format($subtotal, 2) }} EGP</span>
                    </div>





                    <div class="border-t border-gray-200 pt-4 flex justify-between font-bold">
                        <span>Total</span>
                        <span>{{ number_format($total, 2) }} EGP</span>
                    </div>
                </div>


                <div class="space-y-3">
                    <a href="{{ route('checkout') }}" class="w-full bg-dark-brown hover:bg-yellow-700 text-white py-3 rounded-md font-medium transition text-center block">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('home') }}" class="w-full border border-dark-brown text-dark-brown hover:bg-yellow-50 py-3 rounded-md font-medium transition text-center block">
                        Continue Shopping
                    </a>

                </div>
            </div>
        </div>
    </div>

</div>



