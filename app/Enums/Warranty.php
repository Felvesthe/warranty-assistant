<?php

declare(strict_types=1);

namespace App\Enums;

enum Warranty: int
{
    case None = 0;
    case Months_6 = 6;
    case Year_1 = 12;
    case Months_18 = 18;
    case Year_2 = 24;
    case Year_3 = 36;
    case Year_4 = 48;
    case Year_5 = 60;
    case Year_10 = 120;
    case Lifetime = 999;

    public function label(): string
    {
        return match ($this) {
            self::None => __('warranties.none'),
            self::Lifetime => __('warranties.lifetime'),
            self::Months_18 => __('warranties.months_18'),
            default => $this->value >= 12
                ? ($this->value / 12) . ' ' . $this->pluralizeYears($this->value / 12)
                : $this->value . ' ' . __('warranties.months'),
        };
    }

    private function pluralizeYears(int $year): string
    {
        if ($year === 1) {
            return __('warranties.year');
        }
        if ($year >= 2 && $year <= 4) {
            return __('warranties.years');
        }

        return __('warranties.years_over_4');
    }
}
