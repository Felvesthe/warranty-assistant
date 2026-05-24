<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Native\Mobile\Facades\Device;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $languages = ['pl', 'en'];
        $preferredLanguage = null;

        if (($info = Device::getInfo()) !== null) {
            $deviceInfo = json_decode($info, true);

            if (is_array($deviceInfo) && ! empty($deviceInfo['language']) && is_string($deviceInfo['language'])) {
                $deviceLang = explode('-', $deviceInfo['language'])[0];

                if (in_array($deviceLang, $languages)) {
                    $preferredLanguage = $deviceLang;
                }
            }
        }

        if (! $preferredLanguage) {
            $preferredLanguage = $request->getPreferredLanguage($languages);
        }

        if ($preferredLanguage === null) {
            $preferredLanguage = 'pl';
        }

        App::setLocale($preferredLanguage);

        return $next($request);
    }
}
