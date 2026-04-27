<?php

declare(strict_types=1);

namespace App\Settings;

use App\Enums\Currency;
use Spatie\LaravelSettings\Settings;

class UserSettings extends Settings
{
    public string $theme = 'system';

    public Currency $currency = Currency::Polish_zloty;

    public static function group(): string
    {
        return 'default';
    }
}
