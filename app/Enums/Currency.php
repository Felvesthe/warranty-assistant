<?php

declare(strict_types=1);

namespace App\Enums;

enum Currency: string
{
    case Euro = 'eur';
    case Dolar = 'usd';
    case Polish_zloty = 'pln';

    public function symbol(): string
    {
        return match ($this) {
            self::Euro => '€',
            self::Dolar => '$',
            self::Polish_zloty => 'zł',
        };
    }
}
