<div>
    <h2 class="text-xl font-semibold text-gray-900 my-20">Order Summary</h2>

    @if(config('app.debug'))
        <div class="text-xs text-gray-500 mb-2">
            Currency: {{ $currencyCode ?? 'EGP' }} ({{ $currencySymbol ?? 'E£' }}) | Items: {{ isset($cartItems) ? count($cartItems) : 0 }}
        </div>
    @endif

    @if(empty($cartItems))
        <div class="text-center py-8">
            <p class="text-gray-500">Your cart is empty</p>
        </div>
    @else
        <!-- Cart Items -->
        @foreach($cartItems as $item)
        <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
            <div class="flex items-center">
                @if(isset($item['attributes']['image']))
                    <img src="{{ $item['attributes']['image'] }}"
                         alt="{{ $item['name'] }}"
                         class="w-12 h-12 object-cover rounded mr-3">
                @endif
                <div>
                    <h3 class="font-medium text-gray-900">{{ $item['name'] }}</h3>
                    @if(isset($item['attributes']['size']) || isset($item['attributes']['wick_type']))
                        <p class="text-sm text-gray-600">
                            @if(isset($item['attributes']['size'])){{ $item['attributes']['size'] }}gm @endif
                            @if(isset($item['attributes']['size']) && isset($item['attributes']['wick_type'])), @endif
                            @if(isset($item['attributes']['wick_type'])){{ $item['attributes']['wick_type'] }} wick @endif
                        </p>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-900">Qty: {{ $item['quantity'] }}</p>
                <p class="font-medium text-gray-900">{{ $currencySymbol ?? 'E£' }}{{ number_format($item['converted_price'], 2) }}</p>
            </div>
        </div>
        @endforeach

        <!-- Coupon Section -->
        <div class="bg-white rounded-lg shadow-md p-2 mb-6">
            <h4 class="text-sm font-medium text-gray-900 my-2">Discount Code</h4>

            <!-- Notification Messages -->
            @if($couponMessage)
                <div class="mb-4 p-3 rounded-md {{ $couponMessageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' }}"
                     @if($couponMessageType === 'success')
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 5000)"
                     @else
                        x-data="{ show: true }"
                        x-show="show"
                     @endif>
                    <div class="flex justify-between items-center">
                        <span>{{ $couponMessage }}</span>
                        <button @click="show = false" class="ml-2 text-gray-500 hover:text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if(!empty($appliedCouponCode ?? null))
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-sm text-gray-600">Applied coupon:</span>
                        <span class="ml-2 font-semibold">{{ $appliedCouponCode }}</span>
                    </div>
                    <button wire:click="removeCoupon"
                            class="text-sm text-red-600 hover:underline">
                        Remove
                    </button>
                </div>
                @if(($couponDiscount ?? 0) > 0)
                    <div class="mt-2 text-sm text-green-700">
                        Discount: -{{ number_format($couponDiscount, 2) }} {{ $currencySymbol ?? 'E£' }}
                    </div>
                @endif
            @else
                <form wire:submit.prevent="applyCoupon" class="flex items-center gap-2">
                    <input type="text"
                           wire:model.defer="couponCode"
                           placeholder="Enter coupon code"
                           class="flex-1 border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-dark-brown focus:border-transparent">
                    <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-dark-brown rounded-md hover:bg-yellow-700 transition-colors">
                        Apply
                    </button>
                </form>
                @error('couponCode')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            @endif
        </div>

        <!-- Order Totals -->
        <div class="mt-6 space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Subtotal:</span>
                <span class="text-gray-900">{{ $currencySymbol ?? 'E£' }}{{ number_format($subtotal ?? 0, 2) }}</span>
            </div>

            @if(($taxAmount ?? 0) > 0)
            <div class="flex justify-between">
                <span class="text-gray-600">Tax:</span>
                <span class="text-gray-900">{{ $currencySymbol ?? 'E£' }}{{ number_format($taxAmount, 2) }}</span>
            </div>
            @endif

            @if(($shippingAmount ?? 0) > 0)
            <div class="flex justify-between">
                <span class="text-gray-600">Shipping:</span>
                <span class="text-gray-900">{{ $currencySymbol ?? 'E£' }}{{ number_format($shippingAmount, 2) }}</span>
            </div>
            @endif

            @if(($couponDiscount ?? 0) > 0 && !empty($appliedCouponCode ?? null))
            <div class="flex justify-between text-green-700">
                <span>Coupon ({{ $appliedCouponCode }})</span>
                <span>-{{ $currencySymbol ?? 'E£' }}{{ number_format($couponDiscount, 2) }}</span>
            </div>
            @endif

            @if(($loyaltyDiscount ?? 0) > 0)
            <div class="flex justify-between text-green-600">
                <span>Loyalty Points Discount:</span>
                <span>-{{ $currencySymbol ?? 'E£' }}{{ number_format($loyaltyDiscount, 2) }}</span>
            </div>
            @endif

            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                @if(($loyaltyDiscount ?? 0) > 0 || ($couponDiscount ?? 0) > 0)
                    <span class="text-gray-900">Final Total:</span>
                    <span class="text-gray-900">{{ $currencySymbol ?? 'E£' }}{{ number_format($finalTotal ?? 0, 2) }}</span>
                @else
                    <span class="text-gray-900">Total:</span>
                    <span class="text-gray-900">{{ $currencySymbol ?? 'E£' }}{{ number_format($total ?? 0, 2) }}</span>
                @endif
            </div>

            @if(($loyaltyDiscount ?? 0) > 0 || ($couponDiscount ?? 0) > 0)
            <div class="text-sm text-gray-500 text-center">
                @if(($loyaltyDiscount ?? 0) > 0)
                    <p>You saved {{ $currencySymbol ?? 'E£' }}{{ number_format($loyaltyDiscount, 2) }} with loyalty points!</p>
                @endif
                @if(($couponDiscount ?? 0) > 0)
                    <p>You saved {{ $currencySymbol ?? 'E£' }}{{ number_format($couponDiscount, 2) }} with coupon {{ $appliedCouponCode }}!</p>
                @endif
            </div>
            @endif
        </div>
    @endif
</div>

@if(config('app.debug'))
<script>
console.log('💰 OrderSummary: Using pure Livewire events (Alpine.js approach)');
window.testOrderSummaryEvent = function() {
    console.log('🧪 Testing OrderSummary refresh...');
    if (window.Livewire) {
        const orderSummary = document.querySelector('[wire\\:id*="order-summary"]');
        if (orderSummary) {
            const wireId = orderSummary.getAttribute('wire:id');
            const component = window.Livewire.find(wireId);
            if (component) {
                component.$refresh();
                console.log('✅ OrderSummary refreshed manually');
            }
        }
    }
};
</script>
@endif
