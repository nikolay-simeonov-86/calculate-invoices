<?php

namespace App\Enumerations;

final class SupportedCurrencies
{
    const USD = 'usd'
        , EUR = 'eur'
        , GBP = 'gbp'
    ;

    const SUPPORTED_CURRENCIES = [
        self::USD => 'USD',
        self::EUR => 'EUR',
        self::GBP => 'gbp',
    ];

    /**
     * @return string[]
     */
    public static function getSupportedCurrencies()
    {
        return self::SUPPORTED_CURRENCIES;
    }

    /**
     * @param string $currency
     * @return bool
     */
    public static function checkIfCurrencyIsSupported(string $currency)
    {
        return array_key_exists($currency, self::SUPPORTED_CURRENCIES);
    }
}