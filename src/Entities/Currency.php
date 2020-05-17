<?php

namespace App\Entities;

class Currency
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $value;

    /**
     * Currency constructor.
     * @param string $name
     * @param float $value
     */
    public function __construct(string $name, float $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}