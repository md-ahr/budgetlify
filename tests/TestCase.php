<?php

namespace Tests;

use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    /**
     * Shape matches currency-api: payload[baseCurrency][code] = units of code per 1 base.
     *
     * @var array<string, float>
     */
    protected static array $exchangeRatesMap = [
        'usd' => 1.0,
        'eur' => 1.0,
        'bdt' => 1.0,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        self::$exchangeRatesMap = [
            'usd' => 1.0,
            'eur' => 1.0,
            'bdt' => 1.0,
        ];

        /**
         * Use one stub that reads {@see $exchangeRatesMap} so tests can change rates
         * without stacking Http::fake (Laravel uses the first matching stub only).
         */
        Http::fake(function (Request $request, array $options) {
            if ($request->url() !== ExchangeRateService::resolveExchangeRateUrl()) {
                return;
            }

            $base = strtolower((string) config('budgetlify.base_currency', 'usd'));

            return Http::response([
                'date' => '2026-01-01',
                $base => self::$exchangeRatesMap,
            ], 200);
        });
    }

    /**
     * @param  array<string, float>  $unitsPerBase  currency code => units per 1 ledger base unit
     */
    protected function setExchangeRatesForTesting(array $unitsPerBase): void
    {
        foreach ($unitsPerBase as $code => $rate) {
            self::$exchangeRatesMap[strtolower((string) $code)] = (float) $rate;
        }

        Cache::flush();
    }

    /**
     * @param  array<string, float>  $map  full payload[base] map for the HTTP fake
     */
    protected function replaceExchangeRatesMapForTesting(array $map): void
    {
        self::$exchangeRatesMap = $map;
        Cache::flush();
    }
}
