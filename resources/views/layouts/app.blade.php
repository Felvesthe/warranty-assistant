@php
use App\Settings\UserSettings;

$theme = app(UserSettings::class)->theme;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">

        <title>{{ $title ?? __('Warranty Assistant') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

        <script>
            window.SheafConfig = {
                initialTheme: @js($theme)
            };

            const loadDarkMode = () => {
                const theme = @js($theme);

                if (
                    theme === 'dark' ||
                    (theme === 'system' &&
                        window.matchMedia('(prefers-color-scheme: dark)')
                            .matches)
                ) {
                    document.documentElement.classList.add('dark')
                }
            }

            loadDarkMode();

            document.addEventListener('livewire:navigated', function () {
                loadDarkMode();
            });
        </script>
    </head>
    <body class="antialiased nativephp-safe-area">
        <x-top-bar/>

        <div class="mx-3">
            {{ $slot }}
        </div>

        <livewire:navigation/>

        @livewireScriptConfig
    </body>
</html>
