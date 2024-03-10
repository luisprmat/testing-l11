<?php

use App\Services\CurrencyService;

test('convert usd to cop successful', function () {
    $convertedCurrency = (new CurrencyService())->convert(100, 'usd', 'cop');

    expect($convertedCurrency)
        ->toBeFloat()
        ->toEqual(391156.0);
});

test('convert usd to gbp returns zero', function () {
    $convertedCurrency = (new CurrencyService())->convert(100, 'usd', 'gbp');

    expect($convertedCurrency)
        ->toBeFloat()
        ->toEqual(0.0);
});
