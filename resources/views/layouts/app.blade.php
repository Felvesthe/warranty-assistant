<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">

        <title>{{ $title ?? __('Warranty Assistant') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="antialiased nativephp-safe-area">
        <x-top-bar />

        <div class="mx-3">
            {{ $slot }}
        </div>

        <x-navigation />

        @livewireScriptConfig
    </body>
</html>
