<?php

namespace App\Services\ExchangeRate;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService implements ExchangeRateServiceInterface
{
    private const CACHE_KEY = 'exchange_rate_usd_eur';
    private const CACHE_TTL = 3600; // 1 hour

    public function getRate(string $from = 'USD', string $to = 'EUR'): float
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                $response = Http::timeout(5)
                    ->get('https://open.er-api.com/v6/latest/USD');

                if ($response->successful() && isset($response['rates']['EUR'])) {
                    return $response['rates']['EUR'];
                }

                throw new \RuntimeException('Invalid response from exchange rate API');
            } catch (\Exception $e) {
                Log::error('Exchange rate API error: ' . $e->getMessage());
                return config('products.default_exchange_rate');
            }
        });
    }
}