<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ledger base currency
    |--------------------------------------------------------------------------
    |
    | All amounts in the database are stored in this currency. Display and
    | forms use the user's chosen currency with live rates for conversion.
    |
    */

    'base_currency' => env('BUDGETLIFY_BASE_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Exchange rates endpoint
    |--------------------------------------------------------------------------
    |
    | Optional full URL to a currency-api JSON file. Leave null so the URL is built from
    | base_currency (e.g. USD → …/usd.json, EUR → …/eur.json). The top-level key in the JSON
    | must match base_currency. Rates are units of each currency per 1 base unit.
    |
    */
    'exchange_rate_url' => env('BUDGETLIFY_EXCHANGE_URL'),

    'exchange_rate_ttl' => (int) env('BUDGETLIFY_EXCHANGE_TTL', 3600),

];
