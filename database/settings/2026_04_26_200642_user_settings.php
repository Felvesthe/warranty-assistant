<?php

declare(strict_types=1);

use App\Enums\Currency;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('default.theme', 'system');
        $this->migrator->add('default.currency', Currency::Polish_zloty);
    }
};
