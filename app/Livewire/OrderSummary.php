<?php

namespace App\Livewire;

use Exception;
use Livewire\Component;
use App\Services\CartService;
use App\Services\CountryCurrencyService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class OrderSummary extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $taxAmount = 0;
    public $shippingAmount = 0;
    public $total = 0;
    public $currencyCode = 'USD';
    public $currencySymbol = '$';

    // Loyalty points discount properties
    public $loyaltyDiscount = 0;
    public $loyaltyPointsApplied = 0;
    public $finalTotal = 0;

    // Coupon related properties
    public $couponCode = '';
    public $appliedCouponCode = null;
    public $couponDiscount = 0;
    public $couponMessage = '';
    public $couponMessageType = '';

    protected $cartService;
    protected $currencyService;

    public function boot(CartService $cartService, CountryCurrencyService $currencyService)
    {
        $this->cartService = $cartService;
        $this->currencyService = $currencyService;
    }

    public function mount()
    {
        Log::info('OrderSummary: Component mounting');
        $this->loadOrderData();
    }

    #[On('currency-changed')]
    public function handleCurrencyChanged($currencyCode)
    {
        Log::info('OrderSummary: Received currency-changed event', ['currency_code' => $currencyCode]);
        $this->loadOrderData();
    }

    #[On('global-currency-changed')]
    public function handleGlobalCurrencyChanged($currencyCode)
    {
        Log::info('OrderSummary: Received global-currency-changed event', ['currency_code' => $currencyCode]);
        $this->loadOrderData();
    }

    #[On('currencyChanged')]
    public function handleCurrencyChangedEvent($currencyCode)
    {
        Log::info('OrderSummary: Received currencyChanged event', ['currency_code' => $currencyCode]);
        $this->loadOrderData();
    }

    #[On('country-changed')]
    public function handleCountryChanged($countryCode)
    {
        Log::info('OrderSummary: Received country-changed event', ['country_code' => $countryCode]);
        $this->loadOrderData();
    }

    #[On('$refresh')]
    public function handleRefresh()
    {
        Log::info('OrderSummary: Received $refresh event');
        $this->loadOrderData();
    }

    #[On('shipping-updated')]
    public function handleShippingUpdated($shippingAmount)
    {
        Log::info('OrderSummary: Received shipping-updated event', ['shipping_amount' => $shippingAmount]);
        $this->shippingAmount = (float) $shippingAmount;
        $this->total = $this->subtotal + $this->shippingAmount;
        $this->calculateFinalTotal();
    }

    #[On('coupon-applied')]
    public function handleCouponApplied($data)
    {
        Log::info('OrderSummary: Received coupon-applied event', $data);
        $this->appliedCouponCode = $data['code'] ?? null;
        $this->couponDiscount = (float) ($data['discount'] ?? 0);
        $this->calculateFinalTotal();
    }

    #[On('coupon-removed')]
    public function handleCouponRemoved()
    {
        Log::info('OrderSummary: Received coupon-removed event');
        $this->appliedCouponCode = null;
        $this->couponDiscount = 0;
        $this->calculateFinalTotal();
    }

    /**
     * Clear coupon message when user starts typing
     */
    public function updatedCouponCode()
    {
        $this->couponMessage = '';
        $this->couponMessageType = '';
    }

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
                $this->couponMessage = 'Coupon not found.';
                $this->couponMessageType = 'error';
                $this->couponCode = ''; // Clear the input
                return;
            }

            if (!$coupon->isValid()) {
                $this->couponMessage = 'Coupon is not valid or expired.';
                $this->couponMessageType = 'error';
                $this->couponCode = ''; // Clear the input
                return;
            }

            // Get cart subtotal in EGP
            $subtotalEGP = (float) $this->cartService->getSubtotal();

            // All coupon values and min_order_amount are now in EGP
            if ($coupon->type === 'percentage') {
                if (!is_null($coupon->min_order_amount) && $subtotalEGP < (float) $coupon->min_order_amount) {
                    $this->couponMessage = 'Coupon does not meet the minimum order amount of ' . $coupon->min_order_amount . ' EGP.';
                    $this->couponMessageType = 'error';
                    $this->couponCode = ''; // Clear the input
                    return;
                }
                $discount = $subtotalEGP * ((float) $coupon->value / 100);
            } else {
                // Fixed amount coupons (value in EGP)
                $discount = (float) $coupon->value;
            }

            if ($discount <= 0) {
                $this->couponMessage = 'Coupon does not apply to the current total.';
                $this->couponMessageType = 'error';
                $this->couponCode = ''; // Clear the input
                return;
            }

            $this->appliedCouponCode = $coupon->code;
            $this->couponDiscount = round($discount, 2);
            $this->couponMessage = 'Coupon applied successfully! You saved ' . number_format($discount, 2) . ' EGP.';
            $this->couponMessageType = 'success';
            $this->couponCode = ''; // Clear the input

            // Store in session for order processing
            session(['applied_coupon_code' => $this->appliedCouponCode]);
            session(['applied_coupon_id' => $coupon->id]);

            // Dispatch event to CheckoutForm
            $this->dispatch('coupon-applied', [
                'code' => $this->appliedCouponCode,
                'discount' => $this->couponDiscount
            ]);

            $this->calculateFinalTotal();
        } catch (Exception $e) {
            Log::error('Error applying coupon', ['error' => $e->getMessage()]);
            $this->couponMessage = 'Failed to apply coupon. Please try again.';
            $this->couponMessageType = 'error';
            $this->couponCode = ''; // Clear the input
        }
    }

    /**
     * Remove any applied coupon.
     */
    public function removeCoupon()
    {
        $this->appliedCouponCode = null;
        $this->couponDiscount = 0.0;
        $this->couponMessage = 'Coupon removed successfully.';
        $this->couponMessageType = 'success';
        session()->forget(['applied_coupon_code', 'applied_coupon_id']);

        // Dispatch event to CheckoutForm
        $this->dispatch('coupon-removed');

        $this->calculateFinalTotal();
    }

        #[On('loyaltyPointsApplied')]
    public function handleLoyaltyPointsApplied($data)
    {
        Log::info('OrderSummary: Received loyaltyPointsApplied event', $data);

        $this->loyaltyPointsApplied = $data['points'];
        // Convert loyalty discount from USD to local currency using service container as fallback
        $loyaltyDiscountUSD = $data['value'];
        $currencyService = $this->currencyService ?? app(CountryCurrencyService::class);
        $this->loyaltyDiscount = $currencyService->convertFromUSD($loyaltyDiscountUSD, $this->currencyCode);
        $this->calculateFinalTotal();
    }

    #[On('loyaltyPointsRemoved')]
    public function handleLoyaltyPointsRemoved()
    {
        Log::info('OrderSummary: Received loyaltyPointsRemoved event');

        $this->loyaltyPointsApplied = 0;
        $this->loyaltyDiscount = 0;
        $this->calculateFinalTotal();
    }

        #[On('loyaltyPointsUpdated')]
    public function handleLoyaltyPointsUpdated($data)
    {
        Log::info('OrderSummary: Received loyaltyPointsUpdated event', $data);

        // This is just a preview update, don't change the actual applied points
        // Convert loyalty discount from USD to local currency for preview
        $loyaltyDiscountUSD = $data['value'];
        $currencyService = $this->currencyService ?? app(CountryCurrencyService::class);
        $this->loyaltyDiscount = $currencyService->convertFromUSD($loyaltyDiscountUSD, $this->currencyCode);
        $this->calculateFinalTotal();
    }

    protected function calculateFinalTotal()
    {
        $this->finalTotal = max(0, $this->total - $this->loyaltyDiscount - $this->couponDiscount);

        Log::info('OrderSummary: Final total calculated', [
            'original_total' => $this->total,
            'loyalty_discount' => $this->loyaltyDiscount,
            'coupon_discount' => $this->couponDiscount,
            'final_total' => $this->finalTotal
        ]);
    }

    protected function loadOrderData()
    {
        try {
            $cart = $this->cartService->getCart();

            if ($cart->isEmpty()) {
                $this->cartItems = [];
                $this->subtotal = 0;
                $this->taxAmount = 0;
                $this->shippingAmount = 0;
                $this->total = 0;
                $this->finalTotal = 0;
                return;
            }

            // Get current currency preference
            $currencyInfo = $this->currencyService->getCurrentCurrencyInfo();
            $this->currencyCode = $currencyInfo['currency_code'];
            $this->currencySymbol = $currencyInfo['currency_symbol'];

            // Get base prices in USD
            $baseSubtotal = $this->cartService->getSubtotal();
            $baseTaxAmount = $this->cartService->getTaxAmount();
            $baseShippingAmount = $this->cartService->getShippingCost();
            $baseTotal = $this->cartService->getTotal();

            // Convert prices to preferred currency
            $this->subtotal = $this->currencyService->convertFromUSD($baseSubtotal, $this->currencyCode);
            $this->taxAmount = $this->currencyService->convertFromUSD($baseTaxAmount, $this->currencyCode);
            $this->shippingAmount = $this->currencyService->convertFromUSD($baseShippingAmount, $this->currencyCode);
            $this->total = $this->currencyService->convertFromUSD($baseTotal, $this->currencyCode);

            // Convert cart item prices
            $this->cartItems = [];
            foreach ($cart as $item) {
                $convertedPrice = $this->currencyService->convertFromUSD($item['price'], $this->currencyCode);
                $this->cartItems[] = array_merge($item, [
                    'converted_price' => $convertedPrice
                ]);
            }

            // Calculate final total with any existing loyalty discount
            $this->calculateFinalTotal();

            Log::info('OrderSummary: Data loaded', [
                'currency' => $this->currencyCode,
                'symbol' => $this->currencySymbol,
                'subtotal' => $this->subtotal,
                'total' => $this->total,
                'loyalty_discount' => $this->loyaltyDiscount,
                'final_total' => $this->finalTotal,
                'item_count' => count($this->cartItems)
            ]);

        } catch (Exception $e) {
            Log::error('OrderSummary: Error loading data', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.order-summary');
    }
}
