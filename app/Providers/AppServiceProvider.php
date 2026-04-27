<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Native\Mobile\Facades\Device;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict();

        $this->setLanguage();
    }

    private function setLanguage(): void
    {
        if (($info = Device::getInfo()) !== null) {
            $deviceInfo = json_decode($info, true);

            if (! is_array($deviceInfo)) {
                return;
            }

            $language = $deviceInfo['language'];

            if (! (isset($language) && is_string($language))) {
                return;
            }

            $localeCode = explode('-', $language)[0];
            $this->app->setLocale($localeCode);
        }
    }
}
