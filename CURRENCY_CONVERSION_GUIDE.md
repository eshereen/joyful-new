# Currency Conversion System Guide

## Overview
This project has a flexible currency conversion system that can be easily enabled or disabled. All conversion code is preserved and can be activated when needed.

## Default Currency
**Egyptian Pound (EGP)** - All product prices are displayed in Egyptian Pounds by default.

---

## How to Enable/Disable Currency Conversion

### Option 1: Environment Variables (Recommended)
Add these lines to your `.env` file:

```env
# Currency Conversion Settings
# Set to true to enable automatic currency conversion based on user location
# Set to false to show all prices in the default currency (EGP)
CURRENCY_CONVERSION_ENABLED=false

# Default currency and symbol
DEFAULT_CURRENCY=EGP
DEFAULT_CURRENCY_SYMBOL=E£
```

### Option 2: Direct Config File
Edit `/config/currency-converter.php`:

```php
return [
    'conversion_enabled' => true,  // Change to false to disable
    'default_currency' => 'EGP',
    'default_symbol' => 'E£',
];
```

---

## When Conversion is Disabled (Default)
✅ All prices show in Egyptian Pounds (EGP)
✅ No IP detection
✅ No external API calls
✅ Faster page loads
✅ All users see the same prices

## When Conversion is Enabled
✅ Automatic currency detection based on user IP
✅ Converts prices to user's local currency
✅ Uses live exchange rates
✅ Falls back to preset rates if API fails
✅ Users can manually select their currency

---

## Supported Currencies

The system supports the following currencies:

| Currency | Code | Symbol |
|----------|------|--------|
| Egyptian Pound | EGP | E£ |
| US Dollar | USD | $ |
| British Pound | GBP | £ |
| Euro | EUR | € |
| UAE Dirham | AED | د.إ |
| Saudi Riyal | SAR | ر.س |
| Canadian Dollar | CAD | C$ |
| Australian Dollar | AUD | A$ |
| Japanese Yen | JPY | ¥ |
| Swiss Franc | CHF | CHF |
| Indian Rupee | INR | ₹ |
| Brazilian Real | BRL | R$ |
| Mexican Peso | MXN | $ |
| South Korean Won | KRW | ₩ |
| Singapore Dollar | SGD | S$ |
| Hong Kong Dollar | HKD | HK$ |

---

## How It Works

### When Conversion is DISABLED:
1. `CountryCurrencyService::isConversionEnabled()` returns `false`
2. All methods return default currency (EGP)
3. `convertFromUSD()` returns prices as-is
4. No IP detection or API calls
5. Session storage is minimal

### When Conversion is ENABLED:
1. System detects user's country via IP
2. Maps country to currency
3. Converts prices using live rates
4. Stores preference in session
5. User can manually change currency

---

## Code Architecture

### Main Service
**`App\Services\CountryCurrencyService`**

Key methods:
- `isConversionEnabled()` - Checks if conversion is active
- `getDefaultCurrency()` - Returns 'EGP'
- `getDefaultSymbol()` - Returns 'E£'
- `getPreferredCurrency()` - Gets currency (EGP if disabled)
- `convertFromUSD()` - Converts prices (skipped if disabled)

### Livewire Component
**`App\Livewire\ProductIndex`**

- Default currency: EGP
- Checks `isConversionEnabled()` before converting
- Skips conversion when disabled

---

## Enabling Conversion Step-by-Step

### 1. Update .env file:
```env
CURRENCY_CONVERSION_ENABLED=true
```

### 2. Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

### 3. Verify:
- Visit your website
- Check if currency switches based on location
- Manual currency selection should work

---

## Disabling Conversion Step-by-Step

### 1. Update .env file:
```env
CURRENCY_CONVERSION_ENABLED=false
```

### 2. Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

### 3. Verify:
- All prices show in EGP
- No currency selector needed
- Faster page loads

---

## Troubleshooting

### Prices still showing in wrong currency?
```bash
php artisan config:clear
php artisan cache:clear
```

### Currency not detecting correctly?
- Check IP detection service is working
- Verify location package is installed
- Check logs in `storage/logs/laravel.log`

### Want to change default currency to USD?
1. Edit `.env`:
   ```env
   DEFAULT_CURRENCY=USD
   DEFAULT_CURRENCY_SYMBOL=$
   ```
2. Clear config: `php artisan config:clear`

---

## Performance Impact

### With Conversion Disabled:
- ⚡ Instant page loads
- ⚡ No external API calls
- ⚡ No IP lookups
- ⚡ Minimal session data

### With Conversion Enabled:
- 🔄 IP detection (cached per IP)
- 🔄 Currency API calls (cached for 1 hour)
- 🔄 Additional database queries
- 🔄 Session storage overhead

---

## For Developers

### Adding New Currency:
Edit `CountryCurrencyService.php`:

```php
// Add to getCurrencySymbol()
'ARS' => '$',  // Argentine Peso

// Add to mapCountryToCurrency()
'AR' => 'ARS',

// Add to getFallbackRate()
'ARS' => 350.0,
```

### Testing Conversion:
```php
$service = app(CountryCurrencyService::class);
$converted = $service->convertFromUSD(100, 'EGP');
// Returns: 4820.00 (if using fallback rate)
```

---

## Quick Commands

```bash
# Enable conversion
echo "CURRENCY_CONVERSION_ENABLED=true" >> .env
php artisan config:clear

# Disable conversion
echo "CURRENCY_CONVERSION_ENABLED=false" >> .env
php artisan config:clear

# Check current setting
php artisan tinker
>>> config('currency-converter.conversion_enabled')

# Clear all currency caches
php artisan cache:clear
```

---

## Summary

✅ **Default**: Currency conversion is **DISABLED**
✅ **Default Currency**: Egyptian Pound (EGP / E£)
✅ **All code preserved**: Simply flip a switch to enable
✅ **No breaking changes**: Works with existing products
✅ **Easy to toggle**: Change one environment variable

**To enable conversion**: Set `CURRENCY_CONVERSION_ENABLED=true` in `.env`
**To disable conversion**: Set `CURRENCY_CONVERSION_ENABLED=false` in `.env`

