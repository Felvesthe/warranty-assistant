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

                const isDark = theme === 'dark' ||
                    (theme === 'system' &&
                        window.matchMedia('(prefers-color-scheme: dark)')
                            .matches);

                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }

            loadDarkMode();

            document.addEventListener('livewire:navigated', function () {
                loadDarkMode();
            });
        </script>

        <style>
            .statusbar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 999999;
                pointer-events: none;
                height: var(--inset-top, env(safe-area-inset-top, 0px));
                transition: background-color 0.3s;
                background-color: transparent;
            }

            @media (prefers-color-scheme: dark) {
                html:not(.dark) .statusbar-overlay {
                    background-color: var(--color-indigo-600, #4f46e5);
                }
            }

            @media (prefers-color-scheme: light) {
                html.dark .statusbar-overlay {
                    background-color: var(--color-indigo-300, #a5b4fc);
                }
            }
        </style>
    </head>
    <body class="antialiased nativephp-safe-area bg-white text-neutral-900 dark:bg-neutral-950 dark:text-neutral-200">
        <div class="statusbar-overlay"></div>

        <div class="mx-3">
            <x-top-bar/>

            {{ $slot }}
        </div>

        <livewire:navigation/>

        @livewireScriptConfig
    </body>
</html>
