<?php

declare(strict_types=1);

namespace App\Enums;

enum Category: string
{
    case Electronics = 'electronics';
    case AppliancesLarge = 'appliances_large';
    case AppliancesSmall = 'appliances_small';
    case HomeGarden = 'home_garden';
    case Automotive = 'automotive';
    case Sport = 'sport';
    case Fashion = 'fashion';
    case Kids = 'kids';
    case Beauty = 'beauty';
    case Other = 'other';

    public function label(): string
    {
        return __('categories.' . $this->value);
    }

    public function icon(): string
    {
        return match ($this) {
            self::Electronics => 'monitor-smartphone',
            self::AppliancesLarge => 'refrigerator',
            self::AppliancesSmall => 'zap',
            self::HomeGarden => 'house',
            self::Automotive => 'car',
            self::Sport => 'trophy',
            self::Fashion => 'handbag',
            self::Kids => 'baby',
            self::Beauty => 'sparkles',
            self::Other => 'circle-ellipsis',
        };
    }
}
