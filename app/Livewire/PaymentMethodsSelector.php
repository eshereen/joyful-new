<?php

namespace App\Livewire;

use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\PaymentMethodResolver;
use App\Models\Country;
use Livewire\Attributes\On;

class PaymentMethodsSelector extends Component
{
    public array $methods = [];
    public ?string $selectedMethod = null;
    public string $paypalPaymentType = 'credit_card';

                public function mount()
    {
        // Basic debug to see if component is even loading
        error_log('PaymentMethodsSelector: Component mount() called');

        try {
            Log::info('PaymentMethodsSelector: Component mounting');
            $this->loadMethods();

            Log::info('PaymentMethodsSelector: Mount completed', [
                'methods' => $this->methods,
                'selected_method' => $this->selectedMethod,
                'paypal_payment_type' => $this->paypalPaymentType
            ]);

            // Always dispatch initial events after loading methods
            if ($this->selectedMethod) {
                Log::info('PaymentMethodsSelector: Dispatching initial payment-method-selected event', [
                    'method' => $this->selectedMethod
                ]);
                $this->dispatch('payment-method-selected', method: $this->selectedMethod);
            } else {
                Log::warning('PaymentMethodsSelector: No selected method to dispatch initial event');
            }

            if ($this->paypalPaymentType) {
                Log::info('PaymentMethodsSelector: Dispatching initial paypal-payment-type-changed event', [
                    'type' => $this->paypalPaymentType
                ]);
                $this->dispatch('paypal-payment-type-changed', type: $this->paypalPaymentType);
            }

            Log::info('PaymentMethodsSelector: Component mounted successfully');
        } catch (Exception $e) {
            Log::error('PaymentMethodsSelector: Error during mount', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Set default methods in case of error
            $this->methods = ['paypal'];
            $this->selectedMethod = 'paypal';

            // Dispatch default events even in error case
            $this->dispatch('payment-method-selected', method: $this->selectedMethod);
            $this->dispatch('paypal-payment-type-changed', type: $this->paypalPaymentType);
        }
    }

        protected function loadMethods()
    {
        // Get the current country from the checkout form or session
        $countryCode = $this->getCurrentCountryCode();

        $resolver = app(PaymentMethodResolver::class);
        $this->methods = array_map(fn($m) => $m->value, $resolver->availableForCountry($countryCode));

        Log::info('PaymentMethodsSelector: loadMethods - before setting selected method', [
            'country_code' => $countryCode,
            'methods' => $this->methods,
            'current_selected_method' => $this->selectedMethod
        ]);

        // Set default selected method only if none is currently selected
        if (!empty($this->methods) && empty($this->selectedMethod)) {
            $this->selectedMethod = $this->methods[0];
            Log::info('PaymentMethodsSelector: Setting default selected method', [
                'default_method' => $this->selectedMethod
            ]);

            // Dispatch event to notify CheckoutForm of initial selection
            $this->dispatch('payment-method-selected', method: $this->selectedMethod);
        } else {
            Log::info('PaymentMethodsSelector: Keeping existing selected method', [
                'existing_method' => $this->selectedMethod
            ]);
        }

        Log::info('PaymentMethodsSelector: loadMethods completed', [
            'country_code' => $countryCode,
            'methods' => $this->methods,
            'selected_method' => $this->selectedMethod,
            'credit_card_available' => $this->isCreditCardAvailable()
        ]);
    }

    protected function getCurrentCountryCode(): string
    {
        // Try to get from session first (set during checkout)
        if (session()->has('checkout_country')) {
            $countryCode = session('checkout_country');
            Log::info('PaymentMethodsSelector: Country from session', ['country_code' => $countryCode]);
            return $countryCode;
        }

        // Try to get from user's location
        if (Auth::check() && Auth::user()->customer) {
            $countryCode = Auth::user()->customer->country->code ?? 'EG';
            Log::info('PaymentMethodsSelector: Country from user customer', ['country_code' => $countryCode]);
            return $countryCode;
        }

        // Try to get from the checkout form if available
        $checkoutCountry = $this->getCountryFromCheckoutForm();
        if ($checkoutCountry) {
            Log::info('PaymentMethodsSelector: Country from checkout form', ['country_code' => $checkoutCountry]);
            return $checkoutCountry;
        }

        // Default to Egypt
        Log::info('PaymentMethodsSelector: Using default country', ['country_code' => 'EG']);
        return 'EG';
    }

    protected function getCountryFromCheckoutForm(): ?string
    {
        // Try to get country from various form fields
        $request = request();

        // Check shipping address country
        if ($request->has('shipping_address.country')) {
            $countryName = $request->input('shipping_address.country');
            $country = Country::where('name', 'LIKE', $countryName)->first();
            if ($country) {
                return $country->code;
            }
        }

        // Check billing address country
        if ($request->has('billing_address.country')) {
            $countryName = $request->input('billing_address.country');
            $country = Country::where('name', 'LIKE', $countryName)->first();
            if ($country) {
                return $country->code;
            }
        }

        // Check direct country field
        if ($request->has('country')) {
            $countryName = $request->input('country');
            $country = Country::where('name', 'LIKE', $countryName)->first();
            if ($country) {
                return $country->code;
            }
        }

        return null;
    }

    public function updatedSelectedMethod()
    {
        Log::info('PaymentMethodsSelector: updatedSelectedMethod called', [
            'selected_method' => $this->selectedMethod,
            'methods' => $this->methods,
            'component_id' => $this->getId()
        ]);

        $this->dispatch('payment-method-selected', method: $this->selectedMethod);

        Log::info('PaymentMethodsSelector: payment-method-selected event dispatched', [
            'method' => $this->selectedMethod,
            'component_id' => $this->getId()
        ]);

        // Also try dispatching with a different approach - target the checkout-form component specifically
        $this->dispatch('payment-method-selected', method: $this->selectedMethod)->to('checkout-form');

        Log::info('PaymentMethodsSelector: Targeted dispatch to checkout-form attempted');
    }

    public function updatedPaypalPaymentType()
    {
        $this->dispatch('paypal-payment-type-changed', type: $this->paypalPaymentType);
    }

    public function isCreditCardAvailable(): bool
    {
        $countryCode = $this->getCurrentCountryCode();
        $resolver = app(PaymentMethodResolver::class);
        $isAvailable = $resolver->isCreditCardAvailableForCountry($countryCode);

        // Debug logging
        Log::info('PaymentMethodsSelector: Checking credit card availability', [
            'country_code' => $countryCode,
            'is_available' => $isAvailable,
            'methods' => $this->methods,
            'selected_method' => $this->selectedMethod,
            'paypal_payment_type' => $this->paypalPaymentType
        ]);

        return $isAvailable;
    }

    // Listen for country changes from the checkout form
    public function updateForCountry($countryCode)
    {
        try {
            Log::info('PaymentMethodsSelector: updateForCountry called', ['country_code' => $countryCode]);

            // Update the session with the new country code
            session(['checkout_country' => $countryCode]);
            Log::info('PaymentMethodsSelector: Session updated', ['session_country' => session('checkout_country')]);

            $resolver = app(PaymentMethodResolver::class);
            Log::info('PaymentMethodsSelector: PaymentMethodResolver instance created');

            $availableMethods = $resolver->availableForCountry($countryCode);
            Log::info('PaymentMethodsSelector: Available methods from resolver', ['methods' => $availableMethods]);

            $this->methods = array_map(fn($m) => $m->value, $availableMethods);
            Log::info('PaymentMethodsSelector: Methods array updated', ['methods' => $this->methods]);

            // Only set selected method if none is currently selected
            if (!empty($this->methods) && empty($this->selectedMethod)) {
                $this->selectedMethod = $this->methods[0];
                Log::info('PaymentMethodsSelector: Selected method set to first available (none was selected)', ['selected_method' => $this->selectedMethod]);
            } elseif (!empty($this->methods) && $this->selectedMethod) {
                // Check if current selection is still valid for this country
                if (!in_array($this->selectedMethod, $this->methods)) {
                    $this->selectedMethod = $this->methods[0];
                    Log::info('PaymentMethodsSelector: Selected method updated (previous selection not available)', ['selected_method' => $this->selectedMethod]);
                } else {
                    Log::info('PaymentMethodsSelector: Keeping current selection', ['selected_method' => $this->selectedMethod]);
                }
            } else {
                $this->selectedMethod = null;
                Log::info('PaymentMethodsSelector: No methods available, selected method set to null');
            }

            // Always set PayPal payment type to credit card for simplified flow
            $this->paypalPaymentType = 'credit_card';
            Log::info('PaymentMethodsSelector: PayPal payment type set to credit card for simplified flow');

            // Dispatch event to notify CheckoutForm of initial PayPal payment type
            $this->dispatch('paypal-payment-type-changed', type: $this->paypalPaymentType);

            Log::info('PaymentMethodsSelector: Updated for country', [
                'country_code' => $countryCode,
                'methods' => $this->methods,
                'selected_method' => $this->selectedMethod,
                'paypal_payment_type' => $this->paypalPaymentType
            ]);
        } catch (Exception $e) {
            Log::error('PaymentMethodsSelector: Error in updateForCountry', [
                'country_code' => $countryCode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // Listen for refresh event from JavaScript
    public function refreshPaymentMethods()
    {
        try {
            Log::info('PaymentMethodsSelector: refreshPaymentMethods called');
            $this->loadMethods();
            Log::info('PaymentMethodsSelector: Methods refreshed successfully');
        } catch (Exception $e) {
            Log::error('PaymentMethodsSelector: Error in refreshPaymentMethods', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // Listen for update-payment-methods event from JavaScript
    public function updatePaymentMethods($countryCode)
    {
        try {
            Log::info('PaymentMethodsSelector: updatePaymentMethods called via event', ['country_code' => $countryCode]);
            $this->updateForCountry($countryCode);
        } catch (Exception $e) {
            Log::error('PaymentMethodsSelector: Error in updatePaymentMethods', [
                'country_code' => $countryCode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // Method to manually set country and refresh (useful for testing)
    public function setCountry($countryCode)
    {
        Log::info('PaymentMethodsSelector: setCountry called', ['country_code' => $countryCode]);
        session(['checkout_country' => $countryCode]);
        $this->updateForCountry($countryCode);
    }

    // Listen for the refresh-payment-methods event
    protected $listeners = [
        'refresh-payment-methods' => 'refreshPaymentMethods',
        'update-payment-methods' => 'updatePaymentMethods',
        'country-changed' => 'handleCountryChanged'
    ];

    // Listen for country-changed event from JavaScript
    #[On('country-changed')]
    public function handleCountryChanged($data)
    {
        try {
            $countryCode = is_array($data) ? $data['countryCode'] : $data;
            Log::info('PaymentMethodsSelector: Received country-changed event', ['country_code' => $countryCode]);

            if ($countryCode) {
                $this->updateForCountry($countryCode);
            }
        } catch (Exception $e) {
            Log::error('PaymentMethodsSelector: Error handling country-changed event', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Method to manually test payment methods update
    public function testUpdateMethods($countryCode = 'US')
    {
        // Validate the country code
        if (empty($countryCode) || strlen($countryCode) !== 2) {
            Log::error('PaymentMethodsSelector: Invalid country code', ['country_code' => $countryCode]);
            return;
        }

        try {
            Log::info('PaymentMethodsSelector: testUpdateMethods called', ['country_code' => $countryCode]);

            // Log the current state before update
            Log::info('PaymentMethodsSelector: Current state before update', [
                'methods' => $this->methods,
                'selected_method' => $this->selectedMethod,
                'paypal_payment_type' => $this->paypalPaymentType,
                'session_country' => session('checkout_country')
            ]);

            $this->updateForCountry($countryCode);

            // Log the state after update
            Log::info('PaymentMethodsSelector: State after update', [
                'methods' => $this->methods,
                'selected_method' => $this->selectedMethod,
                'paypal_payment_type' => $this->paypalPaymentType,
                'session_country' => session('checkout_country')
            ]);

            Log::info('PaymentMethodsSelector: testUpdateMethods completed successfully');
        } catch (Exception $e) {
            Log::error('PaymentMethodsSelector: testUpdateMethods failed', [
                'country_code' => $countryCode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Re-throw the exception so Livewire can handle it
            throw $e;
        }
    }

    // Simple test method to isolate the issue
    public function simpleTest()
    {
        Log::info('PaymentMethodsSelector: simpleTest called');
        $this->methods = ['paypal'];
        $this->selectedMethod = 'paypal';
        $this->paypalPaymentType = 'paypal_account';
        Log::info('PaymentMethodsSelector: simpleTest completed');
    }

    public function ping()
    {
        Log::info('PaymentMethodsSelector: Ping method called');

        // Test if we can update the form
        $this->dispatch('payment-method-selected', method: 'paypal');
        $this->dispatch('paypal-payment-type-changed', type: 'credit_card');

        return 'Pong! Livewire component is working.';
    }

    // Test method without parameters
    public function testUS()
    {
        Log::info('PaymentMethodsSelector: testUS called');
        $this->updateForCountry('US');
    }

    public function testEG()
    {
        Log::info('PaymentMethodsSelector: testEG called');
        $this->updateForCountry('EG');
    }

    public function render()
    {
        return view('livewire.payment-methods-selector');
    }

    public function onChange($countryId)
    {
        Log::info('PaymentMethodsSelector onChange called', ['countryId' => $countryId]);

        // Get country code from the country ID
        $country = Country::find($countryId);
        if ($country) {
            Log::info('Country found, updating payment methods', [
                'countryId' => $countryId,
                'countryCode' => $country->code,
                'countryName' => $country->name
            ]);
            $this->updateForCountry($country->code);
        } else {
            Log::warning('Country not found for ID', ['countryId' => $countryId]);
        }
    }

    public function testPaymentMethodSelection()
    {
        Log::info('PaymentMethodsSelector: testPaymentMethodSelection called');

        // Test setting COD manually
        $this->selectedMethod = 'cash_on_delivery';
        Log::info('PaymentMethodsSelector: Manually set selectedMethod to COD', [
            'selected_method' => $this->selectedMethod
        ]);

        // Dispatch the event
        $this->dispatch('payment-method-selected', method: $this->selectedMethod);
        Log::info('PaymentMethodsSelector: Dispatched payment-method-selected event for COD');

        // Also try targeted dispatch
        $this->dispatch('payment-method-selected', method: $this->selectedMethod)->to('checkout-form');
        Log::info('PaymentMethodsSelector: Targeted dispatch to checkout-form attempted for COD');
    }
}
