<?php

namespace App\Support;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Auth;

final class UserDate
{
    public static function formatKey(?string $override = null): string
    {
        if ($override !== null && in_array($override, SupportedDateFormats::keys(), true)) {
            return $override;
        }

        $key = Auth::user()?->date_format;
        if (is_string($key) && in_array($key, SupportedDateFormats::keys(), true)) {
            return $key;
        }

        return SupportedDateFormats::DEFAULT_KEY;
    }

    /**
     * Format a calendar date (Y-m-d or datetime) for display. Does not alter stored values.
     */
    public static function format(Carbon|DateTimeInterface|string $value, ?string $formatKey = null): string
    {
        $carbon = $value instanceof Carbon ? $value->copy() : Carbon::parse($value);

        return $carbon->format(SupportedDateFormats::datePattern(self::formatKey($formatKey)));
    }

    /**
     * Format a month context (e.g. budget / analytics period heading).
     */
    public static function formatMonthYear(Carbon|DateTimeInterface|string $value, ?string $formatKey = null): string
    {
        $carbon = $value instanceof Carbon ? $value->copy() : Carbon::parse($value);

        return $carbon->format(SupportedDateFormats::monthPattern(self::formatKey($formatKey)));
    }
}
