<?php

namespace App\Livewire;

use Exception;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Country;
use App\Services\PaymentMethodResolver;
use App\Services\CountryCurrencyService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutForm extends Component
 {
    // Shipping fee for selected state
    public $shippingFee = 0;
    // Subtotal (products price)
    public $subtotal = 0;
    // Total (subtotal + shipping)
    public $total = 0;
    public function updatedBillingState($stateName)
    {
        // Find the state by name
        $state = \App\Models\State::where('name', $stateName)->first();
        if ($state) {
            $shipping = \App\Models\Shipping::where('state_id', $state->id)->first();
            $this->shippingFee = $shipping && $shipping->price !== null ? (float)$shipping->price : 0;
        } else {
            $this->shippingFee = 0;
        }
        $this->updateTotals();
    }


    public function updateTotals()
    {
        // Calculate subtotal (products price)
        $cartService = app(\App\Services\CartService::class);
        $this->subtotal = (float) $cartService->getSubtotal();

        // Calculate total: subtotal + shipping - coupon discount - loyalty discount
        $this->total = $this->subtotal + $this->shippingFee - $this->couponDiscount - $this->loyaltyDiscount;

        // Ensure total is not negative
        if ($this->total < 0) {
            $this->total = 0;
        }

        // Dispatch shipping update to OrderSummary component
        $this->dispatch('shipping-updated', $this->shippingFee);
    }

    // States for selected country
    public $states = [];

    // Customer Information
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $phoneNumber = '';

    // Account Creation
    public $createAccount = false;
    public $password = '';
    public $passwordConfirmation = '';

    // Billing Address fields (also used as shipping address)
    public $billingCountry = null;
    public $billingState = '';
    public $billingCity = '';
    public $billingAddress = '';
    public $billingBuildingNumber = '';

    // Payment fields
    public $paymentMethods = [];
    public $selectedPaymentMethod = null;
    public $paypalPaymentType = 'credit_card';
    public $creditCardAvailable = false;

    // Currency info (always local currency)
    public $currentCurrency = 'EGP';
    public $currentSymbol = 'E£';

    // Loyalty points discount
    public $loyaltyDiscount = 0;
    public $loyaltyPointsApplied = 0;

    // Coupon related fields
    public $couponCode = '';
    public $appliedCouponCode = null;
    public $couponDiscount = 0;

    // Order notes
    public $notes = '';

    /**
     * Apply coupon based on the entered coupon code.
     */
    public function applyCoupon()
    {
        $this->validate([
            'couponCode' => 'required|string',
        ]);

        $code = strtoupper(trim($this->couponCode));

        try {
            $coupon = \App\Models\Coupon::where('code', $code)->first();

            if (!$coupon) {
                session()->flash('error', 'Coupon not found.');
                return;
            }

            if (!$coupon->isValid()) {
                session()->flash('error', 'Coupon is not valid or expired.');
                return;
            }

            // Get cart subtotal in EGP
            $cartService = app(\App\Services\CartService::class);
            $subtotalEGP = (float) $cartService->getSubtotal();

            // All coupon values and min_order_amount are now in EGP
            if ($coupon->type === 'percentage') {
                if (!is_null($coupon->min_order_amount) && $subtotalEGP < (float) $coupon->min_order_amount) {
                    session()->flash('error', 'Coupon does not meet the minimum order amount.');
                    return;
                }
                $discount = $subtotalEGP * ((float) $coupon->value / 100);
            } else {
                // Fixed amount coupons (value in EGP)
                $discount = (float) $coupon->value;
            }

            if ($discount <= 0) {
                session()->flash('error', 'Coupon does not apply to the current total.');
                return;
            }

            $this->appliedCouponCode = $coupon->code;
            $this->couponDiscount = round($discount, 2);

            // Store in session for order processing
            session(['applied_coupon_code' => $this->appliedCouponCode]);
            session(['applied_coupon_id' => $coupon->id]);

            session()->flash('success', 'Coupon applied successfully!');
        } catch (Exception $e) {
            Log::error('Error applying coupon', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to apply coupon.');
        }
    }

    /**
     * Remove any applied coupon.
     */
    public function removeCoupon()
    {
        $this->appliedCouponCode = null;
        $this->couponDiscount = 0.0;
        session()->forget(['applied_coupon_code', 'applied_coupon_id']);
        session()->flash('success', 'Coupon removed.');
    }

    public function mount()
    {
        // Auto-fill customer information if user is logged in
        if (Auth::check()) {
            $user = Auth::user();

            $displayName = trim($user->name ?? '') ?: 'Customer';
            $this->firstName = $displayName;
            $this->lastName = $displayName;

            $this->email = $user->email;

            // Try to get phone number from user or customer table
            if (isset($user->phone_number) && $user->phone_number) {
                $this->phoneNumber = $user->phone_number;
            } elseif ($user->customer && $user->customer->phone_number) {
                $this->phoneNumber = $user->customer->phone_number;
            } else {
                // Set empty string if no phone number found
                $this->phoneNumber = '';
            }

            Log::info('Auto-filled user data for checkout', [
                'user_id' => $user->id,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'email' => $this->email,
                'phoneNumber' => $this->phoneNumber
            ]);
        }

        // Get current country from session or default to Egypt (EG)
        $countryCode = session('checkout_country', 'EG');
        $country = Country::where('code', $countryCode)->first();

        if ($country) {
            // Set billing country
            $this->billingCountry = $country->id;

            // Load states for billing country
            $this->loadStates($this->billingCountry);

            // Update session with the country code
            session(['checkout_country' => $countryCode]);

            // Load payment methods
            $this->updatePaymentMethods($countryCode);

            // Set default currency (always local)
            $currencyService = app(CountryCurrencyService::class);
            $currencyInfo = $currencyService->getCurrentCurrencyInfo();
            $this->currentCurrency = $currencyInfo['currency_code'];
            $this->currentSymbol = $currencyInfo['currency_symbol'];
        }

        // Calculate initial totals
        $this->updateTotals();
        // Check stock availability and show warnings
        $this->checkStockAvailability();
    }
    public function loadStates($countryId)
    {
        if ($countryId) {
            $this->states = \App\Models\State::where('country_id', $countryId)->orderBy('name')->get();
        } else {
            $this->states = [];
        }
    }


    public function updatedBillingCountry($countryId)
    {
        $this->loadStates($countryId);
        $this->billingState = '';
        $this->handleCountryChange($countryId, 'billing');
    }

    protected function handleCountryChange($countryId, $source = 'unknown')
    {
        if (!$countryId) return;

        $country = Country::find($countryId);
        if (!$country) return;

        // Update session
        session(['checkout_country' => $country->code]);

        // Update payment methods
        $this->updatePaymentMethods($country->code);

        // Dispatch events to other components
        $this->dispatch('country-changed', $country->code);

        Log::info('CheckoutForm: Country change complete', [
            'country_code' => $country->code,
            'payment_methods' => $this->paymentMethods,
            'selected_method' => $this->selectedPaymentMethod
        ]);
    }

    protected function updatePaymentMethods($countryCode)
    {
        try {
            $resolver = app(PaymentMethodResolver::class);
            $availableMethods = $resolver->availableForCountry($countryCode);

            $this->paymentMethods = array_map(fn($m) => $m->value, $availableMethods);
            $this->creditCardAvailable = $resolver->isCreditCardAvailableForCountry($countryCode);

            // Don't set default payment method here - let PaymentMethodsSelector handle it
            // The PaymentMethodsSelector will dispatch an event to set this value
            Log::info('CheckoutForm: Payment methods loaded, waiting for PaymentMethodsSelector to set selection', [
                'available_methods' => $this->paymentMethods
            ]);

            // Reset PayPal payment type if credit card not available
            if (!$this->creditCardAvailable) {
                $this->paypalPaymentType = 'credit_card';
            }
        } catch (Exception $e) {

            // Fallback to default methods
            $this->paymentMethods = ['paypal'];
            // Don't set selectedPaymentMethod here - let PaymentMethodsSelector handle it
            $this->creditCardAvailable = false;
        }
    }


    public function getCountriesProperty()
    {
        return Country::orderBy('name')->get();
    }

    // Validation rules for all form fields
    protected function rules()
    {
        $rules = [
            'firstName' => 'required|string|min:2|max:50',
            'lastName' => 'required|string|min:2|max:50',
            'email' => 'required|email|max:255',
            'phoneNumber' => 'required|string|min:10|max:20',

            'billingCountry' => 'required|exists:countries,id',
            'billingState' => 'required|string|min:2|max:100',
            'billingCity' => 'required|string|min:2|max:100',
            'billingAddress' => 'required|string|min:5|max:255',
            'billingBuildingNumber' => 'nullable|string|max:50',

            'selectedPaymentMethod' => 'required|string|in:paypal,paymob,cash_on_delivery,instapay',
        ];

        // Add password validation if user wants to create an account
        if ($this->createAccount && !Auth::check()) {
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8|max:255';
            $rules['passwordConfirmation'] = 'required|same:password';
        }

        return $rules;
    }

    // Custom validation messages
    protected $messages = [
        'firstName.required' => 'First name is required',
        'lastName.required' => 'Last name is required',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered. Please login or use a different email.',
        'phoneNumber.required' => 'Phone number is required',
        'phoneNumber.min' => 'Phone number must be at least 10 characters',

        'billingCountry.required' => 'Billing country is required',
        'billingState.required' => 'State/Province is required',
        'billingCity.required' => 'City is required',
        'billingAddress.required' => 'Billing address is required',

        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 8 characters',
        'passwordConfirmation.required' => 'Please confirm your password',
        'passwordConfirmation.same' => 'Passwords do not match',

        'selectedPaymentMethod.required' => 'Please select a payment method',
        'selectedPaymentMethod.in' => 'Please select a valid payment method',
    ];

    // Real-time validation for specific fields
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // Listen for payment method selection from PaymentMethodsSelector
    #[On('payment-method-selected')]
    public function handlePaymentMethodSelected($method)
    {
        $this->selectedPaymentMethod = $method;
    }

    // Listen for PayPal payment type change
    #[On('paypal-payment-type-changed')]
    public function handlePayPalPaymentTypeChanged($type)
    {
        // Always set to credit card for simplified flow
        $this->paypalPaymentType = 'credit_card';
    }

    // Listen for loyalty points events
    #[On('country-changed')]
    public function handleCountryChanged($countryCode)
    {
        $this->updatePaymentMethods($countryCode);
    }

    #[On('loyaltyPointsApplied')]
    public function handleLoyaltyPointsApplied($data)
    {
        $this->loyaltyPointsApplied = $data['points'];
        // Loyalty discount is already in local currency
        $this->loyaltyDiscount = $data['value'];
    }

    #[On('loyaltyPointsRemoved')]
    public function handleLoyaltyPointsRemoved()
    {
        $this->loyaltyPointsApplied = 0;
        $this->loyaltyDiscount = 0;
    }

    #[On('loyaltyPointsUpdated')]
    public function handleLoyaltyPointsUpdated($data)
    {
        // This is just a preview update, don't change the actual applied points
        // The discount will be shown in the order summary but not stored until applied
    }

    #[On('coupon-applied')]
    public function handleCouponApplied($data)
    {
        Log::info('CheckoutForm: Received coupon-applied event', $data);
        $this->appliedCouponCode = $data['code'] ?? null;
        $this->couponDiscount = (float) ($data['discount'] ?? 0);
        $this->updateTotals();
    }

    #[On('coupon-removed')]
    public function handleCouponRemoved()
    {
        Log::info('CheckoutForm: Received coupon-removed event');
        $this->appliedCouponCode = null;
        $this->couponDiscount = 0;
        $this->updateTotals();
    }

    // Method to validate all form data
    public function validateForm()
    {
        return $this->validate();
    }

    // Method to create user account
    protected function createUserAccount()
    {
        try {
            // Check if user already exists
            $existingUser = \App\Models\User::where('email', $this->email)->first();
            if ($existingUser) {
                Log::warning('Attempted to create account for existing email', ['email' => $this->email]);
                return null;
            }

            // Create new user
            $user = \App\Models\User::create([
                'name' => $this->firstName . ' ' . $this->lastName,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            Log::info('User account created during checkout', ['user_id' => $user->id, 'email' => $user->email]);

            // Log the user in
            Auth::login($user);

            Log::info('User logged in after account creation', ['user_id' => $user->id]);

            return $user;
        } catch (Exception $e) {
            Log::error('Error creating user account during checkout', [
                'error' => $e->getMessage(),
                'email' => $this->email
            ]);
            return null;
        }
    }

    // Method to handle form submission
    public function submitForm()
    {
        try {
            // Validate the form
            $this->validate();

            // Validate stock availability before proceeding
            $this->validateStockAvailability();

            // Create user account if requested and not already logged in
            $userCreated = false;
            if ($this->createAccount && !Auth::check()) {
                $user = $this->createUserAccount();
                if ($user) {
                    $userCreated = true;
                    Log::info('User account created and logged in during checkout', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                }
            }

            // Store form data in session (using billing address as shipping address)
            $sessionData = [
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'billing_country_id' => $this->billingCountry,
                'billing_state' => $this->billingState,
                'billing_city' => $this->billingCity,
                'billing_address' => $this->billingAddress,
                'billing_building_number' => $this->billingBuildingNumber,
                'shipping_country_id' => $this->billingCountry,
                'shipping_state' => $this->billingState,
                'shipping_city' => $this->billingCity,
                'shipping_address' => $this->billingAddress,
                'shipping_building_number' => $this->billingBuildingNumber,
                'use_billing_for_shipping' => true,
                'payment_method' => $this->selectedPaymentMethod,
                'paypal_payment_type' => $this->paypalPaymentType,
                'currency' => $this->currentCurrency,
                'shipping_amount' => $this->shippingFee,
                'subtotal' => $this->subtotal,
                'total_amount' => $this->total,
                'coupon_discount' => $this->couponDiscount,
                'loyalty_discount' => $this->loyaltyDiscount,
                'loyalty_points_applied' => $this->loyaltyPointsApplied,
                'notes' => $this->notes,
            ];

            // Clear any previous session data and set new data
            session(['checkout_data' => $sessionData]);

            // If user was just created, add a flag to handle CSRF properly
            $accountJustCreated = $userCreated ? 'true' : 'false';
            $delayMs = $userCreated ? 1000 : 0; // Add 1 second delay if account was created

            // Submit form via JavaScript with fresh CSRF token
            $this->js('
                const accountJustCreated = ' . $accountJustCreated . ';
                const delayMs = ' . $delayMs . ';

                function submitCheckout() {
                // Refresh CSRF token before submission
                fetch("' . route('csrf.refresh') . '", {
                    method: "GET",
                    credentials: "same-origin",
                    headers: {
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log("✅ CSRF token refreshed successfully");

                    // Update meta tag with fresh token
                    const metaToken = document.querySelector("meta[name=\'csrf-token\']");
                    if (metaToken && data.csrf_token) {
                        metaToken.setAttribute("content", data.csrf_token);
                    }

                    // Use the fresh token
                    const csrfToken = data.csrf_token || (metaToken ? metaToken.getAttribute("content") : "");

                    // Create and submit form
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = "' . route('checkout.process') . '";

                    const csrfInput = document.createElement("input");
                    csrfInput.type = "hidden";
                    csrfInput.name = "_token";
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    document.body.appendChild(form);
                    form.submit();
                })
                .catch(error => {
                    console.error("❌ Error refreshing CSRF token:", error);
                    // Fallback: Try with current token
                    const metaToken = document.querySelector("meta[name=\'csrf-token\']");
                    const csrfToken = metaToken ? metaToken.getAttribute("content") : "";

                    if (csrfToken) {
                        const form = document.createElement("form");
                        form.method = "POST";
                        form.action = "' . route('checkout.process') . '";

                        const csrfInput = document.createElement("input");
                        csrfInput.type = "hidden";
                        csrfInput.name = "_token";
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        alert("Session expired. Please refresh the page and try again.");
                    }
                });
                }

                // If account was just created, wait for session to be properly saved
                if (accountJustCreated && delayMs > 0) {
                    console.log("⏳ Account just created, waiting " + delayMs + "ms for session to save...");
                    setTimeout(submitCheckout, delayMs);
                } else {
                    submitCheckout();
                }
            ');
        } catch (Exception $e) {
            // Log the full error for debugging
            Log::error('Checkout form submission error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'form_data' => [
                    'firstName' => $this->firstName,
                    'lastName' => $this->lastName,
                    'email' => $this->email,
                    'phoneNumber' => $this->phoneNumber,
                    'billingCountry' => $this->billingCountry,
                    'selectedPaymentMethod' => $this->selectedPaymentMethod,
                ]
            ]);

            // Check if it's a validation error
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                // Validation errors are already shown by Livewire
                return;
            }

            // Check if it's a stock-related error
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'out of stock') !== false || strpos($errorMessage, 'Only') !== false) {
                // Show specific stock error with action buttons
                $this->dispatch('showStockError', [
                    'message' => $errorMessage,
                    'type' => 'error',
                    'showCartButton' => true
                ]);
            } else {
                // Show generic error with more details
                $this->dispatch('showNotification', [
                    'message' => 'An error occurred while submitting the form: ' . $errorMessage,
                    'type' => 'error'
                ]);
            }
        }
    }

    /**
     * Check stock availability and show warnings to users
     */
    protected function checkStockAvailability()
    {
        $cart = app(\App\Services\CartService::class)->getCart();
        $stockIssues = [];

        foreach ($cart as $item) {
            $itemType = $this->cartItemType($item);
            $requestedQuantity = $item['quantity'];

            if ($itemType === 'collection') {
                $collectionId = $this->getCollectionIdFromCartItem($item);
                if (!$collectionId) {
                    $stockIssues[] = 'Collection reference missing from cart item.';
                    continue;
                }

                $collection = \App\Models\Collection::find($collectionId);
                if (!$collection) {
                    $stockIssues[] = "Collection not found: {$collectionId}";
                    continue;
                }

                $available = (int) ($collection->stock ?? 0);
                if ($available < $requestedQuantity) {
                    if ($available <= 0) {
                        $stockIssues[] = "❌ Collection '{$collection->name}' is out of stock.";
                    } else {
                        $stockIssues[] = "⚠️ Only {$available} set(s) available for '{$collection->name}'.";
                    }
                }

                continue;
            }

            $productId = null;
            if (strpos($item['id'], '-') !== false) {
                $parts = explode('-', $item['id']);
                $productId = (int) $parts[0];
            } else {
                $productId = (int) $item['id'];
            }

            $variantId = $item['attributes']['variant_id'] ?? null;

            if ($variantId) {
                // Check variant stock
                $variant = \App\Models\Variant::find($variantId);
                if (!$variant) {
                    $stockIssues[] = "Product variant not found: {$variantId}";
                    continue;
                }

                if ($variant->stock < $requestedQuantity) {
                    $product = \App\Models\Product::find($productId);
                    $availableStock = $variant->stock;

                    if ($availableStock <= 0) {
                        $stockIssues[] = "❌ Product '{$product?->name}' (Size: {$variant->size}, Color: {$variant->color}) is out of stock.";
                    } else {
                        $stockIssues[] = "⚠️ Only {$availableStock} items available for '{$product?->name}' (Size: {$variant->size}, Color: {$variant->color}).";
                    }
                }
            } else {
                // Check product stock for simple products
                $product = \App\Models\Product::find($productId);
                if (!$product) {
                    $stockIssues[] = "Product not found: {$productId}";
                    continue;
                }

                if ($product->quantity < $requestedQuantity) {
                    $availableStock = $product->quantity;

                    if ($availableStock <= 0) {
                        $stockIssues[] = "❌ Product '{$product->name}' is out of stock.";
                    } else {
                        $stockIssues[] = "⚠️ Only {$availableStock} items available for '{$product->name}'.";
                    }
                }
            }
        }

        // Show stock warnings if any issues found
        if (!empty($stockIssues)) {
            $message = "Stock Issues Found:\n" . implode("\n", $stockIssues);
            $this->dispatch('showStockError', [
                'message' => $message,
                'type' => 'warning',
                'showCartButton' => true
            ]);
        }
    }

    /**
     * Validate stock availability for all cart items
     */
    protected function validateStockAvailability()
    {
        $cart = app(\App\Services\CartService::class)->getCart();

        foreach ($cart as $item) {
            $itemType = $this->cartItemType($item);
            $requestedQuantity = $item['quantity'];

            if ($itemType === 'collection') {
                $collectionId = $this->getCollectionIdFromCartItem($item);
                if (!$collectionId) {
                    throw new \Exception('Collection reference missing from cart item.');
                }

                $collection = \App\Models\Collection::find($collectionId);
                if (!$collection) {
                    throw new \Exception("Collection not found: {$collectionId}");
                }

                $available = (int) ($collection->stock ?? 0);
                if ($available < $requestedQuantity) {
                    if ($available <= 0) {
                        throw new \Exception("❌ Collection '{$collection->name}' is currently out of stock. Please remove it from your cart.");
                    }

                    throw new \Exception("⚠️ Only {$available} set(s) available for '{$collection->name}'. Please reduce the quantity in your cart.");
                }

                continue;
            }

            $productId = null;
            if (strpos($item['id'], '-') !== false) {
                $parts = explode('-', $item['id']);
                $productId = (int) $parts[0];
            } else {
                $productId = (int) $item['id'];
            }

            $variantId = $item['attributes']['variant_id'] ?? null;

            if ($variantId) {
                // Check variant stock
                $variant = \App\Models\Variant::find($variantId);
                if (!$variant) {
                    throw new \Exception("Product variant not found: {$variantId}");
                }

                if ($variant->stock < $requestedQuantity) {
                    $product = \App\Models\Product::find($productId);
                    $availableStock = $variant->stock;

                    if ($availableStock <= 0) {
                        throw new \Exception("❌ Product '{$product?->name}' (Size: {$variant->size}, Color: {$variant->color}) is currently out of stock. Please remove it from your cart or try a different variant.");
                    } else {
                        throw new \Exception("⚠️ Only {$availableStock} items available for '{$product?->name}' (Size: {$variant->size}, Color: {$variant->color}). Please reduce the quantity in your cart.");
                    }
                }
            } else {
                // Check product stock for simple products
                $product = \App\Models\Product::find($productId);
                if (!$product) {
                    throw new \Exception("Product not found: {$productId}");
                }

                if ($product->quantity < $requestedQuantity) {
                    $availableStock = $product->quantity;

                    if ($availableStock <= 0) {
                        throw new \Exception("❌ Product '{$product->name}' is currently out of stock. Please remove it from your cart.");
                    } else {
                        throw new \Exception("⚠️ Only {$availableStock} items available for '{$product->name}'. Please reduce the quantity in your cart.");
                    }
                }
            }
        }
    }

    protected function cartItemType(array $item): string
    {
        if (($item['attributes']['item_type'] ?? null) === 'collection') {
            return 'collection';
        }

        if (isset($item['id']) && Str::startsWith($item['id'], 'collection-')) {
            return 'collection';
        }

        return 'product';
    }

    protected function getCollectionIdFromCartItem(array $item): ?int
    {
        if (isset($item['attributes']['collection_id'])) {
            return (int) $item['attributes']['collection_id'];
        }

        if (isset($item['id']) && Str::startsWith($item['id'], 'collection-')) {
            return (int) Str::after($item['id'], 'collection-');
        }

        return null;
    }

    public function render()
    {
        return view('livewire.checkout-form');
    }
}
