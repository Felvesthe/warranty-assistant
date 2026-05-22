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

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
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
    <body class="antialiased nativephp-safe-area bg-white text-neutral-900 dark:bg-neutral-950 dark:text-neutral-200">
        <div class="mx-3">
            <x-top-bar/>

            {{ $slot }}
        </div>

        <livewire:navigation/>

        @livewireScriptConfig
    </body>
</html>
