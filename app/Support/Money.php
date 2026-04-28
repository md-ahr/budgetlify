<?php

namespace App\Support;

use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

final class Money
{
    public static function currencyCode(?string $override = null): string
    {
        if ($override !== null) {
            $normalized = strtoupper(trim($override));
            if (in_array($normalized, SupportedCurrencies::CODES, true)) {
                return $normalized;
            }
        }

        $raw = Auth::user()?->currency;
        if (is_string($raw)) {
            $code = strtoupper(trim($raw));
            if (in_array($code, SupportedCurrencies::CODES, true)) {
                return $code;
            }
        }

        return 'USD';
    }

    /**
     * Short symbol shown beside amount inputs (user display currency).
     */
    public static function amountFieldPrefix(): string
    {
        return match (self::currencyCode()) {
            'EUR' => '€',
            'BDT' => '৳',
            default => '$',
        };
    }

    /**
     * ICU locale for {@see format()} so USD, EUR, and BDT use conventional grouping, decimals, and symbols.
     */
    public static function formatLocale(?string $currencyCode = null): string
    {
        $code = self::currencyCode($currencyCode);

        return match ($code) {
            'EUR' => 'de_DE',
            'BDT' => 'bn_BD',
            default => 'en_US',
        };
    }

    /**
     * Convert a stored (base currency) amount to the user's display currency.
     */
    public static function toDisplayAmount(float|int $amountBase, ?string $currencyCode = null): float
    {
        $code = self::currencyCode($currencyCode);

        return app(ExchangeRateService::class)->fromBaseTo((float) $amountBase, $code);
    }

    /**
     * Convert a user-entered display amount to base currency for persistence.
     */
    public static function toBaseAmount(float|int $amountDisplay, ?string $currencyCode = null): float
    {
        $code = self::currencyCode($currencyCode);

        return app(ExchangeRateService::class)->toBase((float) $amountDisplay, $code);
    }

    /**
     * Format a stored amount (base currency): converts to display currency, then Intl format.
     */
    public static function format(float|int $amountBase, ?int $fractionDigits = 2, ?string $currencyCode = null): string
    {
        $code = self::currencyCode($currencyCode);
        $displayAmount = self::toDisplayAmount((float) $amountBase, $code);
        $locale = self::formatLocale($code);
        $digits = $fractionDigits ?? 2;
        $formatted = Number::currency($displayAmount, $code, $locale, $digits);

        if (is_string($formatted) && $formatted !== '') {
            return $formatted;
        }

        return number_format($displayAmount, $digits, '.', ',').' '.$code;
    }
}
