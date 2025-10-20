<?php

namespace App\Services;

use App\Models\Country;
use Exception;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;
use Carbon\Carbon;

class CountryCurrencyService
{
    /**
     * Check if currency conversion is enabled
     */
    public function isConversionEnabled(): bool
    {
        return config('currency-converter.conversion_enabled', false);
    }

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

    public function detectCountry()
    {
        // If conversion is disabled, return default currency
        if (!$this->isConversionEnabled()) {
            return [
                'country_code' => 'EG',
                'country_name' => 'Egypt',
                'currency_code' => $this->getDefaultCurrency()
            ];
        }

        $ip = request()->ip();

        // Skip detection for localhost/development
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return [
                'country_code' => 'EG',
                'country_name' => 'Egypt',
                'currency_code' => $this->getDefaultCurrency()
            ];
        }

        // Cache detection per IP to avoid repeated slow lookups
        return Cache::remember("detected_country_{$ip}", Carbon::now()->addDays(1), function () use ($ip) {
            Log::info("CountryCurrencyService: Detecting country for IP: {$ip}");

            try {
                // Set a timeout to prevent hanging
                $location = Location::get($ip);
                Log::info("CountryCurrencyService: Location result", ['location' => $location]);

                if ($location) {
                    $result = [
                        'country_code' => $location->countryCode,
                        'country_name' => $location->countryName,
                        'currency_code' => $this->mapCountryToCurrency($location->countryCode)
                    ];
                    Log::info("CountryCurrencyService: Detection successful", $result);
                    return $result;
                }
            } catch (Exception $e) {
                Log::error("CountryCurrencyService: Location detection failed: " . $e->getMessage());
            }

            Log::warning("CountryCurrencyService: Could not detect location for IP: {$ip}");
            return null;
        });
    }

    public function getPreferredCurrency()
    {
        // If conversion is disabled, always return default currency
        if (!$this->isConversionEnabled()) {
            return $this->getDefaultCurrency();
        }

        // First, check if user has manually selected a currency
        if (Session::has('preferred_currency')) {
            return Session::get('preferred_currency');
        }

        // Then check if user has a preferred country
        if (Session::has('preferred_country_id')) {
            $country = Country::find(Session::get('preferred_country_id'));
            if ($country) {
                return $country->currency_code;
            }
        }

        // If we previously auto-detected, reuse that to avoid re-detecting every request
        if (Session::has('detected_currency')) {
            return Session::get('detected_currency');
        }

        // Finally, fall back to IP detection (memoized by cache) and store in session
        $detected = $this->detectCountry();
        if ($detected && $detected['currency_code']) {
            Session::put('detected_country', $detected['country_code']);
            Session::put('detected_currency', $detected['currency_code']);
            return $detected['currency_code'];
        }
        // Default to configured default currency
        return $this->getDefaultCurrency();
    }

    public function setPreferredCurrency($currencyCode)
    {
        // Clear all currency-related session data to force a fresh start
        Session::forget('preferred_country_id');
        Session::forget('detected_country');
        Session::forget('detected_currency');

        // Set the new currency preference
        Session::put('preferred_currency', $currencyCode);
        Session::put('currency_initialized', true);

        Log::info("CountryCurrencyService: Currency changed to {$currencyCode}", [
            'session_cleared' => true,
            'new_currency' => $currencyCode
        ]);
    }

    public function setPreferredCountry($countryId)
    {
        $country = Country::find($countryId);
        if ($country) {
            Session::put('preferred_country_id', $countryId);

            // Do not override a manual currency selection
            $hasManualSelection = Session::has('preferred_currency') && Session::get('currency_initialized') === true;
            if (!$hasManualSelection) {
                Session::put('preferred_currency', $country->currency_code);
            }
        }
    }

    public function getCurrentCurrencyInfo()
    {
        $currencyCode = $this->getPreferredCurrency();

        // Cache the currency info to avoid repeated queries
        return cache()->remember("currency_info_{$currencyCode}", 300, function () use ($currencyCode) {
            $country = null;

            // Find the country for this currency
            if (Session::has('preferred_country_id')) {
                $country = Country::find(Session::get('preferred_country_id'));
            } else {
                // Try to find a country with this currency
                $country = Country::select('id','code','currency_code','currency_sympol')
                    ->where('currency_code', $currencyCode)
                    ->first();
            }

            return [
                'currency_code' => $currencyCode,
                'currency_symbol' => $this->getCurrencySymbol($currencyCode),
                'country' => $country,
                'is_auto_detected' => !Session::has('preferred_currency'),
            ];
        });
    }

    public function convertFromUSD($amount, $currencyCode)
    {
        // If conversion is disabled, return amount as-is (assuming prices are already in default currency)
        if (!$this->isConversionEnabled()) {
            return $amount;
        }

        if (!$currencyCode || $currencyCode === 'USD') {
            return $amount;
        }

        try {
            // Cache conversion result for 1 hour
            $cacheKey = "usd_to_{$currencyCode}_{$amount}";
            $converted = Cache::remember($cacheKey, Carbon::now()->addHours(1), function () use ($amount, $currencyCode) {
                try {
                    // Use the new currency converter package
                    $result = CurrencyConverter::convert($amount)
                        ->from('USD')
                        ->to($currencyCode)
                        ->get();

                    return round($result, 2);
                } catch (Exception $e) {
                    Log::error("CurrencyConverter package error: " . $e->getMessage());
                    // Fallback to manual rate calculation
                    return $this->convertWithFallbackRate($amount, $currencyCode);
                }
            });

            Log::info("Currency conversion: {$amount} USD to {$currencyCode} = {$converted}");
            return $converted;
        } catch (Exception $e) {
            Log::error("Currency conversion error: " . $e->getMessage());
            // Use fallback conversion
            return $this->convertWithFallbackRate($amount, $currencyCode);
        }
    }

    protected function convertWithFallbackRate($amount, $currencyCode)
    {
        $rate = $this->getFallbackRate($currencyCode);
        $converted = round($amount * $rate, 2);
        Log::warning("Using fallback rate for {$currencyCode}: {$amount} USD = {$converted} {$currencyCode} (rate: {$rate})");
        return $converted;
    }

    protected function getFallbackRate($currencyCode)
    {
        // Fallback rates (updated periodically) - these are approximate
        $fallbackRates = [
            'EGP' => 48.20,    // Egyptian Pound
            'GBP' => 0.79,    // British Pound
            'EUR' => 0.92,    // Euro
            'AED' => 3.67,    // UAE Dirham
            'SAR' => 3.75,    // Saudi Riyal
            'CAD' => 1.35,    // Canadian Dollar
            'AUD' => 1.52,    // Australian Dollar
            'JPY' => 150.0,   // Japanese Yen
            'CHF' => 0.88,    // Swiss Franc
            'INR' => 83.0,    // Indian Rupee
            'BRL' => 4.95,    // Brazilian Real
            'MXN' => 17.0,    // Mexican Peso
            'KRW' => 1350.0,  // South Korean Won
            'SGD' => 1.35,    // Singapore Dollar
            'HKD' => 7.82,    // Hong Kong Dollar
        ];

        return $fallbackRates[$currencyCode] ?? 1.0;
    }

    public function getCurrencySymbol($currencyCode)
    {
        $symbols = [
            'USD' => '$',
            'EGP' => 'E£',
            'GBP' => '£',
            'EUR' => '€',
            'AED' => 'د.إ',
            'SAR' => 'ر.س',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'JPY' => '¥',
            'CHF' => 'CHF',
        ];

        return $symbols[$currencyCode] ?? $currencyCode;
    }

    public function getCountryCurrency($countryId)
    {
        $country = Country::find($countryId);
        return $country ? $country->currency_code : 'USD';
    }

    public function getCountryCurrencyByCode($countryCode)
    {
        $country = Country::select('id','code','currency_code','currency_sympol')->where('code', $countryCode)->first();
        return $country ? $country->currency_code : 'USD';
    }

    public function convertCartToCurrency($cartData, $countryId = null)
    {
        if ($countryId) {
            $currencyCode = $this->getCountryCurrency($countryId);
        } else {
            $currencyCode = $this->getPreferredCurrency();
        }

        if ($currencyCode === 'USD') {
            return $cartData;
        }

        $converted = [];
        foreach ($cartData as $key => $value) {
            if (is_numeric($value) && in_array($key, ['subtotal', 'tax_amount', 'shipping_amount', 'total'])) {
                $converted[$key] = $this->convertFromUSD($value, $currencyCode);
            } else {
                $converted[$key] = $value;
            }
        }

        return $converted;
    }

    private function mapCountryToCurrency($countryCode)
    {
        // Extended mapping for more countries
        $map = [
            'EG' => 'EGP',
            'US' => 'USD',
            'GB' => 'GBP',
            'EU' => 'EUR',
            'AE' => 'AED',
            'SA' => 'SAR',
            'CA' => 'CAD',
            'AU' => 'AUD',
            'JP' => 'JPY',
            'CH' => 'CHF',
            'IN' => 'INR',
            'BR' => 'BRL',
            'MX' => 'MXN',
            'KR' => 'KRW',
            'SG' => 'SGD',
            'HK' => 'HKD',
        ];

        return $map[$countryCode] ?? 'USD';
    }
}
