<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    public function __construct(
        private Repository $cache,
    ) {}

    /**
     * Resolve the HTTP URL for the currency-api JSON file whose top-level key matches
     * {@see config('budgetlify.base_currency')} (e.g. usd.json → payload["usd"]).
     *
     * When {@see config('budgetlify.exchange_rate_url')} is set, that URL is used as-is
     * (must return the same JSON shape for your configured base currency).
     */
    public static function resolveExchangeRateUrl(): string
    {
        $configured = config('budgetlify.exchange_rate_url');
        if (is_string($configured) && $configured !== '') {
            return $configured;
        }

        $base = strtolower((string) config('budgetlify.base_currency', 'usd'));

        return "https://latest.currency-api.pages.dev/v1/currencies/{$base}.json";
    }

    /**
     * Convert an amount from the app base currency (e.g. USD) to $toCurrency.
     */
    public function fromBaseTo(float $amount, string $toCurrency): float
    {
        $to = strtoupper($toCurrency);
        $base = strtoupper((string) config('budgetlify.base_currency', 'USD'));

        if ($to === $base) {
            return round($amount, 2);
        }

        $rate = $this->unitsPerBase($to);

        return round($amount * $rate, 2);
    }

    /**
     * Convert an amount from $fromCurrency into base currency for persistence.
     */
    public function toBase(float $amount, string $fromCurrency): float
    {
        $from = strtoupper($fromCurrency);
        $base = strtoupper((string) config('budgetlify.base_currency', 'USD'));

        if ($from === $base) {
            return round($amount, 2);
        }

        $rate = $this->unitsPerBase($from);
        if ($rate <= 0.0) {
            return round($amount, 2);
        }

        return round($amount / $rate, 2);
    }

    /**
     * Units of $currency per 1 base unit (e.g. with base USD: BDT per 1 USD, EUR per 1 USD).
     */
    public function unitsPerBase(string $currency): float
    {
        $code = strtoupper($currency);
        $base = strtoupper((string) config('budgetlify.base_currency', 'USD'));

        if ($code === $base) {
            return 1.0;
        }

        $rates = $this->rates();
        $key = strtolower($code);

        if (! isset($rates[$key])) {
            return 1.0;
        }

        return max(0.0, (float) $rates[$key]);
    }

    /**
     * @return array<string, float> lowercase currency code => units per 1 base
     */
    private function rates(): array
    {
        $base = strtolower((string) config('budgetlify.base_currency', 'usd'));
        $ttl = (int) config('budgetlify.exchange_rate_ttl', 3600);

        return $this->cache->remember("budgetlify.rates.v2.{$base}", max(60, $ttl), function () use ($base): array {
            return $this->fetchRates($base);
        });
    }

    /**
     * @return array<string, float>
     */
    private function fetchRates(string $base): array
    {
        foreach ($this->rateFeedUrls($base) as $url) {
            if ($url === '') {
                $map = $this->attemptFetchOpenErLatest($base);
            } else {
                $map = $this->attemptFetchCurrencyApiJson($url, $base);
            }
            if ($map !== null && $map !== []) {
                return $map;
            }
        }

        Log::warning('budgetlify.exchange_rates_using_1_1_fallback', [
            'base' => $base,
            'hint' => 'All exchange HTTP sources failed or returned invalid JSON. Clear config cache and run: php artisan cache:clear',
        ]);

        return $this->fallbackRates();
    }

    /**
     * Ordered list of feeds. Empty string means open.er-api (different JSON shape).
     *
     * @return list<string>
     */
    private function rateFeedUrls(string $base): array
    {
        $urls = [self::resolveExchangeRateUrl(), ''];

        $usingDefaultHost = config('budgetlify.exchange_rate_url') === null
            || config('budgetlify.exchange_rate_url') === '';

        if ($usingDefaultHost) {
            $urls[] = "https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/{$base}.json";
        }

        return array_values(array_unique($urls));
    }

    /**
     * @return array<string, float>|null
     */
    private function attemptFetchCurrencyApiJson(string $url, string $base): ?array
    {
        try {
            $response = Http::timeout(12)
                ->acceptJson()
                ->withHeaders(['User-Agent' => 'Budgetlify/1.0 (https://github.com)'])
                ->get($url);

            if (! $response->successful()) {
                Log::warning('budgetlify.exchange_rates_http', ['status' => $response->status(), 'url' => $url]);

                return null;
            }

            /** @var mixed $payload */
            $payload = $response->json();
            if (! is_array($payload) || ! isset($payload[$base]) || ! is_array($payload[$base])) {
                Log::warning('budgetlify.exchange_rates_shape', ['url' => $url, 'base' => $base]);

                return null;
            }

            /** @var array<string, mixed> $rawMap */
            $rawMap = $payload[$base];
            $out = [];
            foreach ($rawMap as $code => $value) {
                if (is_numeric($value)) {
                    $out[strtolower((string) $code)] = (float) $value;
                }
            }

            return $out !== [] ? $out : null;
        } catch (\Throwable $e) {
            Log::warning('budgetlify.exchange_rates_exception', ['message' => $e->getMessage(), 'url' => $url]);

            return null;
        }
    }

    /**
     * open.er-api returns { "result": "success", "rates": { "EUR": 0.85, ... } } for GET /v6/latest/USD.
     *
     * @return array<string, float>|null
     */
    private function attemptFetchOpenErLatest(string $base): ?array
    {
        $url = 'https://open.er-api.com/v6/latest/'.strtoupper($base);

        try {
            $response = Http::timeout(12)
                ->acceptJson()
                ->withHeaders(['User-Agent' => 'Budgetlify/1.0 (https://github.com)'])
                ->get($url);

            if (! $response->successful()) {
                Log::warning('budgetlify.exchange_rates_http', ['status' => $response->status(), 'url' => $url]);

                return null;
            }

            /** @var mixed $payload */
            $payload = $response->json();
            if (! is_array($payload) || ($payload['result'] ?? '') !== 'success') {
                Log::warning('budgetlify.exchange_rates_open_er_shape', ['url' => $url]);

                return null;
            }

            /** @var mixed $ratesRaw */
            $ratesRaw = $payload['rates'] ?? null;
            if (! is_array($ratesRaw)) {
                return null;
            }

            $out = [];
            foreach ($ratesRaw as $code => $value) {
                if (is_numeric($value)) {
                    $out[strtolower((string) $code)] = (float) $value;
                }
            }

            return $out !== [] ? $out : null;
        } catch (\Throwable $e) {
            Log::warning('budgetlify.exchange_rates_exception', ['message' => $e->getMessage(), 'url' => $url]);

            return null;
        }
    }

    /**
     * @return array<string, float>
     */
    private function fallbackRates(): array
    {
        return [
            'usd' => 1.0,
            'eur' => 1.0,
            'bdt' => 1.0,
        ];
    }
}
