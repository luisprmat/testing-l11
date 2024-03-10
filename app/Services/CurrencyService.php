<?php

namespace App\Services;

class CurrencyService
{
    const RATES = [
        'usd' => [
            'cop' => 3911.56,
        ],
    ];

    public function convert(float $amount, string $currencyFrom, string $currencyTo): float
    {
        $rate = self::RATES[$currencyFrom][$currencyTo] ?? 0;

        return round($amount * $rate, 2);
    }
}
