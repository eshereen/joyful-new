<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Currency Conversion Settings
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic currency conversion based on user location.
    | When disabled, all prices will be shown in the default currency.
    |
    */
    'conversion_enabled' => env('CURRENCY_CONVERSION_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The default currency for all prices in the application.
    | This is the base currency that products are priced in.
    |
    */
    'default_currency' => env('DEFAULT_CURRENCY', 'EGP'),
    'default_symbol' => env('DEFAULT_CURRENCY_SYMBOL', 'E£'),

    /*
    |--------------------------------------------------------------------------
    | Currency Format Settings
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'format' => [
            'decimals' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
        ],
    ],
];
