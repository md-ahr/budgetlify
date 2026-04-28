<?php

namespace App\Support;

final class SupportedCurrencies
{
    /**
     * @var list<string>
     */
    public const CODES = [
        'USD',
        'EUR',
        'BDT',
    ];

    /**
     * @return list<array{code: string, label: string}>
     */
    public static function options(): array
    {
        return [
            ['code' => 'USD', 'label' => __('US Dollar (USD)')],
            ['code' => 'EUR', 'label' => __('EURO (EUR)')],
            ['code' => 'BDT', 'label' => __('Bangladeshi Taka (BDT)')],
        ];
    }
}
