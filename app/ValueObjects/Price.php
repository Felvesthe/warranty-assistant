<?php

declare(strict_types=1);

namespace App\ValueObjects;

readonly class Price
{
    public int $cent;

    public float $dollar;

    public function __construct(int $cent)
    {
        $this->cent = $cent;
        $this->dollar = $cent / 100;
    }

    public static function fromCent(int $cent): self
    {
        return new self($cent);
    }

    public static function fromDollar(float $dollar): self
    {
        $cent = (int) (round($dollar, 2) * 100);

        return new self($cent);
    }
}
