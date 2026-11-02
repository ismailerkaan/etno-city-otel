<?php

namespace App\Support;

class Price
{
    private const SYMBOLS = ['TRY' => '₺', 'EUR' => '€', 'USD' => '$', 'GBP' => '£'];

    /**
     * Format an amount with its currency, e.g. "₺1.930" or "€868,50".
     */
    public static function format(float|int|string|null $amount, ?string $currency = 'TRY'): string
    {
        $amount = (float) $amount;
        $currency = strtoupper($currency ?: 'TRY');
        $decimals = fmod($amount, 1.0) === 0.0 ? 0 : 2;
        $number = number_format($amount, $decimals, ',', '.');

        return isset(self::SYMBOLS[$currency]) ? self::SYMBOLS[$currency].$number : $number.' '.$currency;
    }
}
