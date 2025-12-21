<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Session;

class CountryCurrencyService
{
    /**
     * Get the default currency from config
     */
    public function getDefaultCurrency(): string
    {
        return config('currency-converter.default_currency', 'EGP');
    }

    /**
     * Get the default currency symbol from config
     */
    public function getDefaultSymbol(): string
    {
        return config('currency-converter.default_symbol', 'E£');
    }

    /**
     * Get preferred currency (always returns default since no conversion)
     */
    public function getPreferredCurrency(): string
    {
        return $this->getDefaultCurrency();
    }

    /**
     * Set preferred currency (no-op since we only use local currency)
     */
    public function setPreferredCurrency($currencyCode)
    {
        // No-op: We only use local currency
    }

    /**
     * Set preferred country (no-op since we only use local currency)
     */
    public function setPreferredCountry($countryId)
    {
        // No-op: We only use local currency
    }

    /**
     * Get current currency info (always returns default)
     */
    public function getCurrentCurrencyInfo()
    {
        return [
            'currency_code' => $this->getDefaultCurrency(),
            'currency_symbol' => $this->getDefaultSymbol(),
            'country' => null,
            'is_auto_detected' => false,
        ];
    }

    /**
     * Convert from USD - returns amount as-is since we only use local currency
     */
    public function convertFromUSD($amount, $currencyCode = null)
    {
        // No conversion needed - prices are already in local currency
        return $amount;
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbol($currencyCode = null)
    {
        return $this->getDefaultSymbol();
    }

    /**
     * Get country currency
     */
    public function getCountryCurrency($countryId)
    {
        return $this->getDefaultCurrency();
    }

    /**
     * Get country currency by code
     */
    public function getCountryCurrencyByCode($countryCode)
    {
        return $this->getDefaultCurrency();
    }

    /**
     * Convert cart to currency - returns cart data as-is
     */
    public function convertCartToCurrency($cartData, $countryId = null)
    {
        // No conversion needed - prices are already in local currency
        return $cartData;
    }
}
