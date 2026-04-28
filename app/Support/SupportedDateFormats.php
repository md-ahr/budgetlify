<?php

namespace App\Support;

final class SupportedDateFormats
{
    public const DEFAULT_KEY = 'locale_long_us';

    /**
     * @var array<string, array{date: string, month: string, label: string}>
     */
    private const PRESETS = [
        'iso' => [
            'date' => 'Y-m-d',
            'month' => 'Y-m',
            'label' => 'ISO (2026-04-28)',
        ],
        'us_slash' => [
            'date' => 'm/d/Y',
            'month' => 'M Y',
            'label' => 'US numeric (04/28/2026)',
        ],
        'eu_slash' => [
            'date' => 'd/m/Y',
            'month' => 'M Y',
            'label' => 'Europe numeric (28/04/2026)',
        ],
        'de_dot' => [
            'date' => 'd.m.Y',
            'month' => 'M Y',
            'label' => 'Germany (28.04.2026)',
        ],
        'locale_long_us' => [
            'date' => 'M j, Y',
            'month' => 'F Y',
            'label' => 'Long US (Apr 28, 2026)',
        ],
        'locale_long_eu' => [
            'date' => 'j M Y',
            'month' => 'F Y',
            'label' => 'Long EU (28 Apr 2026)',
        ],
    ];

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::PRESETS);
    }

    public static function datePattern(string $key): string
    {
        return self::PRESETS[$key]['date'] ?? self::PRESETS[self::DEFAULT_KEY]['date'];
    }

    public static function monthPattern(string $key): string
    {
        return self::PRESETS[$key]['month'] ?? self::PRESETS[self::DEFAULT_KEY]['month'];
    }

    /**
     * @return list<array{key: string, label: string}>
     */
    public static function options(): array
    {
        $out = [];
        foreach (self::PRESETS as $key => $row) {
            $out[] = [
                'key' => $key,
                'label' => __($row['label']),
            ];
        }

        return $out;
    }
}
