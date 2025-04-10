<?php

namespace App\Services\ExchangeRate;

interface ExchangeRateServiceInterface
{
    public function getRate(string $from = 'USD', string $to = 'EUR'): float;
}