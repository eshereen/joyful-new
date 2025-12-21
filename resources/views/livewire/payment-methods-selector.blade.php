<div>
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Payment Method</h2>

    <div class="space-y-4">
        @foreach($methods as $method)
            <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:border-red-300 transition">
                <input type="radio"
                       name="payment_method_selector"
                       value="{{ $method }}"
                       wire:model.live="selectedMethod"
                       class="mt-1 mr-3 text-dark-brown focus:ring-dark-brown border-gray-300">
                <div class="flex-1">
                    <div class="flex items-center">
                        {{-- COMMENTED OUT: PayPal and Paymob icons - will be activated later --}}
                        {{-- @if($method === 'paypal')
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.067 8.478c.492.315.844.825.844 1.478 0 .653-.352 1.163-.844 1.478-.492.315-1.163.478-1.844.478H17.5v-2.956h.723c.681 0 1.352.163 1.844.478zM20.067 12.478c.492.315.844.825.844 1.478 0 .653-.352 1.163-.844 1.478-.492.315-1.163.478-1.844.478H17.5v-2.956h.723c.681 0 1.352.163 1.844.478z"/>
                            </svg>
                        @elseif($method === 'paymob')
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        @elseif($method === 'cash_on_delivery') --}}
                        @if($method === 'cash_on_delivery')
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        @elseif($method === 'instapay')
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                        {{-- @endif --}}
                        <span class="font-medium text-gray-900">
                            {{-- COMMENTED OUT: Paymob condition - will be activated later --}}
                            {{-- @if($method === 'paymob')
                                Credit Card
                            @else --}}
                                {{ ucfirst(str_replace('_',' ', $method)) }}
                            {{-- @endif --}}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{-- COMMENTED OUT: PayPal and Paymob descriptions - will be activated later --}}
                        {{-- @if($method === 'paypal')
                            Pay with your PayPal account
                        @elseif($method === 'paymob')
                            Pay securely with your credit or debit card
                        @elseif($method === 'cash_on_delivery') --}}
                        @if($method === 'cash_on_delivery')
                            Pay with cash when your order is delivered
                        @elseif($method === 'instapay')
                            Pay with Instapay and send payment screenshot via WhatsApp
                        @endif
                        {{-- @endif --}}
                    </p>

                    {{-- Instapay Accordion/Dropdown --}}
                    @if($method === 'instapay')
                        <div x-data="{ open: false }" class="mt-3">
                            <button
                                type="button"
                                @click="open = !open"
                                class="flex items-center justify-between w-full text-left text-sm font-medium text-dark-brown hover:text-dark-brown/80 focus:outline-none focus:ring-2 focus:ring-dark-brown focus:ring-offset-2 rounded-md p-2"
                            >
                                <span>Payment Instructions</span>
                                <svg
                                    class="w-5 h-5 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200"
                            >
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 mb-1">Pay with Instapay to:</p>
                                        <p class="text-base font-semibold text-dark-brown">{{ config('app.instapay_phone', '+20 100 000 0000') }}</p>
                                    </div>

                                    <div class="pt-2 border-t border-gray-200">
                                        <p class="text-sm font-medium text-gray-900 mb-2">After payment, send screenshot via WhatsApp:</p>
                                        <a
                                            href="https://wa.me/{{ str_replace([' ', '+', '-'], '', config('app.instapay_phone', '+201000000000')) }}?text=Order%20Payment%20Screenshot"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            Send Screenshot via WhatsApp
                                        </a>
                                    </div>

                                    <div class="pt-2 border-t border-gray-200">
                                        <p class="text-xs text-gray-600">
                                            <strong>Note:</strong> Please send the payment screenshot within 24 hours to confirm your order.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- COMMENTED OUT: PayPal additional information - will be activated later --}}
                    {{-- @if($method === 'paypal')
                        <div class="mt-3">
                            <div class="text-xs text-gray-500">
                                <p>• Pay with PayPal account or credit/debit card</p>
                                <p>• Secure payment processing by PayPal</p>
                                <p>• Choose payment method on PayPal's secure page</p>
                            </div>
                        </div>
                    @endif --}}
                </div>
            </label>
        @endforeach
    </div>

    @error('payment_method')
        <p class="text-dark-brown text-sm mt-2">{{ $message }}</p>
    @enderror

    <!-- Hidden input for PayPal payment type - REMOVED to prevent form submission issues -->
    <!-- <input type="hidden" name="paypal_payment_type" value="{{ $paypalPaymentType }}"> -->



</div>

