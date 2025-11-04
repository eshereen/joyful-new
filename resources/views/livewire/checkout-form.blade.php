<div>
    @if(!Auth::check())
    <!-- Customer Information - Only for Guest Users -->
    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-xl font-semibold text-gray-900">Customer Information</h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="first_name" class="block mb-1 text-sm font-medium text-gray-700">First Name *</label>
                <input type="text" wire:model.live="firstName" id="first_name" name="customer[first_name]" required
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                @error('firstName') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="last_name" class="block mb-1 text-sm font-medium text-gray-700">Last Name *</label>
                <input type="text" wire:model.live="lastName" id="last_name" name="customer[last_name]" required
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                @error('lastName') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email Address *</label>
                <input type="email" wire:model.live="email" id="email" name="customer[email]" required
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                @error('email') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone_number" class="block mb-1 text-sm font-medium text-gray-700">Phone Number *</label>
                <input type="tel" wire:model.live="phoneNumber" id="phone_number" name="customer[phone_number]" required
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                @error('phoneNumber') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Create Account Option -->
        <div class="pt-4 mt-4 border-t border-gray-200">
            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="createAccount" class="mr-2 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <span class="text-sm font-medium text-gray-700">Create an account for faster checkout next time</span>
                </label>
            </div>

            @if($createAccount)
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password *</label>
                    <input type="password" wire:model.live="password" id="password" name="password" required
                           class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           placeholder="Minimum 8 characters">
                    @error('password') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">Confirm Password *</label>
                    <input type="password" wire:model.live="passwordConfirmation" id="password_confirmation" name="password_confirmation" required
                           class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           placeholder="Re-enter password">
                    @error('passwordConfirmation') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                <i class="mr-1 fas fa-info-circle"></i>
                Your account will be created automatically and you'll be logged in after placing your order.
            </p>
            @endif
        </div>
    </div>
    @else
    <!-- Hidden Fields for Logged-in Users -->
    <input type="hidden" wire:model="firstName" value="{{ $firstName }}">
    <input type="hidden" wire:model="lastName" value="{{ $lastName }}">
    <input type="hidden" wire:model="email" value="{{ $email }}">

    <!-- Welcome Message for Logged-in Users -->
    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Welcome back, {{ $firstName }}!</h2>
                    <p class="mt-1 text-sm text-gray-600">We've pre-filled your information to make checkout faster.</p>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class="text-green-500 fas fa-check-circle"></i>
                    <span>Logged in as <strong>{{ $email }}</strong></span>
                </div>
            </div>

            <!-- Phone Number Field - Required for all users -->
            @if(empty($phoneNumber))
            <div class="pt-4 border-t border-gray-200">
                <label for="phone_number_logged" class="block mb-1 text-sm font-medium text-gray-700">Phone Number * (Required for order updates)</label>
                <input type="tel" wire:model.live="phoneNumber" id="phone_number_logged" name="phone_number" required
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                       placeholder="Enter your phone number">
                @error('phoneNumber') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
            @else
            <input type="hidden" wire:model="phoneNumber" value="{{ $phoneNumber }}">
            @endif
        </div>
    </div>
    @endif

    <!-- Billing Address -->
    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-xl font-semibold text-gray-900">Shipping Address</h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="billing_country" class="block mb-1 text-sm font-medium text-gray-700">Country *</label>
                <select wire:model.live="billingCountry" id="billing_country" name="billing_address[country]" required
                        class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Select a country</option>
                    @foreach($this->countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                @error('billingCountry') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="billing_state" class="block mb-1 text-sm font-medium text-gray-700">State/Province *</label>
                <select wire:model.live="billingState" id="billing_state" name="billing_address[state]" required
                        class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Select a state</option>
                    @foreach($states as $state)
                        <option value="{{ $state->name }}">{{ $state->name }}</option>
                    @endforeach
                </select>
                @error('billingState') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="billing_city" class="block mb-1 text-sm font-medium text-gray-700">City *</label>
                <input type="text" wire:model.live="billingCity" id="billing_city" name="billing_address[city]" required
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                @error('billingCity') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
               <div class="md:col-span-2">
                <label for="billing_address" class="block mb-1 text-sm font-medium text-gray-700">Street Address *</label>
                <input type="text" wire:model.live="billingAddress" id="billing_address" name="billing_address[address]" required
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                @error('billingAddress') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label for="billing_building_number" class="block mb-1 text-sm font-medium text-gray-700">Building Number (Optional)</label>
                <input type="text" wire:model.live="billingBuildingNumber" id="billing_building_number" name="billing_address[building_number]"
                       class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                @error('billingBuildingNumber') <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>


    <!-- Order Notes -->
    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-xl font-semibold text-gray-900">Additional Information</h2>

        <div class="mb-4">
            <label for="notes" class="block mb-1 text-sm font-medium text-gray-700">Order Notes (Optional)</label>
            <textarea id="notes"
                      wire:model.live="notes"
                      name="notes"
                      rows="4"
                      placeholder="Add any special instructions or notes for your order..."
                      class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
            @error('notes')
                <p class="mt-1 text-sm text-dark-brown">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Payment Methods Selector -->
    @livewire('payment-methods-selector')




        <!-- Submit Button -->
    <div class="p-6 bg-white rounded-lg shadow-md">
        <button type="button"
                wire:click="submitForm"
                wire:loading.attr="disabled"
                wire:target="submitForm"
                class="px-6 py-3 w-full font-bold text-white rounded-lg transition-colors bg-dark-brown hover:bg-yellow-700 disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="submitForm">
                {{ Auth::check() ? 'Place Order' : 'Place Order as Guest' }}
            </span>
            <span wire:loading wire:target="submitForm" class="flex justify-center items-center">
                <svg class="mr-3 -ml-1 w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </button>
    </div>

    <!-- Loading and Error Messages -->
    <div wire:loading wire:target="submitForm" class="px-4 py-3 mb-4 text-blue-700 bg-blue-50 rounded border border-blue-200">
        <div class="flex items-center">
            <svg class="mr-3 -ml-1 w-5 h-5 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing your order...
        </div>
    </div>



    <!-- Hidden Form for Submission -->
    <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}" style="display: none;">
        @csrf
        <input type="hidden" name="first_name" value="{{ $firstName }}">
        <input type="hidden" name="last_name" value="{{ $lastName }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
        <input type="hidden" name="billing_country_id" value="{{ $billingCountry }}">
        <input type="hidden" name="billing_state" value="{{ $billingState }}">
        <input type="hidden" name="billing_city" value="{{ $billingCity }}">
        <input type="hidden" name="billing_address" value="{{ $billingAddress }}">
        <input type="hidden" name="billing_building_number" value="{{ $billingBuildingNumber ?: 'N/A' }}">
        <input type="hidden" name="shipping_country_id" value="{{ $billingCountry }}">
        <input type="hidden" name="shipping_state" value="{{ $billingState }}">
        <input type="hidden" name="shipping_city" value="{{ $billingCity }}">
        <input type="hidden" name="shipping_address" value="{{ $billingAddress }}">
        <input type="hidden" name="shipping_building_number" value="{{ $billingBuildingNumber ?: 'N/A' }}">
        <input type="hidden" name="use_billing_for_shipping" value="1">
        <input type="hidden" name="payment_method" value="{{ $selectedPaymentMethod }}">
        <input type="hidden" name="paypal_payment_type" value="credit_card">
    <input type="hidden" name="currency" value="EGP">
        @if($appliedCouponCode)
        <input type="hidden" name="coupon_code" value="{{ $appliedCouponCode }}">
        <input type="hidden" name="coupon_discount" value="{{ $couponDiscount }}">
        @endif
        <input type="hidden" name="notes" value="{{ $notes }}">
    </form>

</div>

<script>
// Refresh CSRF token periodically to prevent expiration during long checkout sessions
(function() {
    let tokenRefreshInterval;

    // Refresh CSRF token every 10 minutes (600000ms)
    function startTokenRefresh() {
        tokenRefreshInterval = setInterval(async function() {
            try {
                const response = await fetch('{{ route('csrf.refresh') }}', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('✅ CSRF token refreshed successfully (background)');

                    // Update meta tag with fresh token
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag && data.csrf_token) {
                        metaTag.setAttribute('content', data.csrf_token);
                    }
                }
            } catch (error) {
                console.error('❌ Error refreshing CSRF token:', error);
            }
        }, 600000); // 10 minutes
    }

    // Start when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startTokenRefresh);
    } else {
        startTokenRefresh();
    }

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        if (tokenRefreshInterval) {
            clearInterval(tokenRefreshInterval);
        }
    });
})();
</script>

