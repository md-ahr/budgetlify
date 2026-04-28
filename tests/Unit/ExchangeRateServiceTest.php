<?php

use App\Services\ExchangeRateService;
use Tests\TestCase;

uses(TestCase::class);

it('converts display BDT to base USD using fetched rates', function () {
    $this->setExchangeRatesForTesting(['bdt' => 100.0]);

    $service = app(ExchangeRateService::class);

    expect($service->toBase(30_000.0, 'BDT'))->toBe(300.0);
    expect($service->fromBaseTo(300.0, 'BDT'))->toBe(30_000.0);
});

it('converts USD ledger amounts to EUR using euro-per-USD rate', function () {
    config(['budgetlify.base_currency' => 'USD']);
    $this->setExchangeRatesForTesting(['eur' => 0.85]);

    $service = app(ExchangeRateService::class);

    expect($service->fromBaseTo(100.0, 'EUR'))->toBe(85.0);
    expect($service->toBase(85.0, 'EUR'))->toBe(100.0);
});

it('resolves euro-based rates when ledger base currency is EUR', function () {
    config(['budgetlify.base_currency' => 'EUR']);
    config(['budgetlify.exchange_rate_url' => null]);

    $this->replaceExchangeRatesMapForTesting([
        'eur' => 1.0,
        'usd' => 1.10,
        'bdt' => 130.0,
    ]);

    $service = app(ExchangeRateService::class);

    expect($service->fromBaseTo(100.0, 'USD'))->toBe(110.0);
    expect($service->toBase(110.0, 'USD'))->toBe(100.0);
});
