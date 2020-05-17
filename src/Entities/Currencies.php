<?php

namespace App\Entities;

class Currencies
{
    /**
     * @var Currency[]
     */
    private $currencies;

    /**
     * @return Currency[]
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * @param Currency[] $currencies
     */
    public function setCurrencies(array $currencies): void
    {
        $this->currencies = $currencies;
    }

    /**
     * @param string $currencyName
     * @return float
     */
    public function getCurrencyValueByName(string $currencyName): float
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getName() === $currencyName) {
                return $currency->getValue();
            }
        }

        return 1.0;
    }
}