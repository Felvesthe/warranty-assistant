<?php

use Livewire\Component;
use Native\Mobile\Facades\Dialog;
use Illuminate\Support\Facades\File;

new class extends Component {
    public function openAppLicense(): void
    {
        $path = base_path('LICENSE');
        Dialog::alert(__('settings.app_license'), File::exists($path) ? File::get($path) : __('settings.not_found'));
    }
};
?>

<div>
    <x-ui.heading level="h2" size="xs" class="mb-1 uppercase font-bold">{{ __('Application') }}</x-ui.heading>
    <x-ui.card class="p-0 text-sm shadow">
        <div class="flex items-center gap-5 m-3">
            <x-lucide-smartphone class="p-2.5 w-10 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('Version') }}</x-ui.heading>
                <p class="text-xs">{{ config()->string('app.version') }}</p>
            </div>
        </div>

        <x-ui.separator/>

        <div class="flex items-center gap-5 m-3">
            <x-lucide-user class="p-2.5 w-10 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('Author') }}</x-ui.heading>
                <p class="text-xs">Sebastian Bobiński</p>
            </div>
        </div>

        <x-ui.separator/>

        <a href="https://github.com/Felvesthe/warranty-assistant" class="flex items-center gap-5 m-3 active:opacity-70 transition-opacity">
            <x-lucide-code class="p-2.5 w-10 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('Source code') }}</x-ui.heading>
                <p class="text-xs">github.com/Felvesthe/warranty-assistant</p>
            </div>
        </a>

        <x-ui.separator/>

        <div wire:click="openAppLicense" class="flex items-center gap-5 m-3 active:opacity-70 transition-opacity cursor-pointer">
            <x-lucide-file-text class="p-2.5 w-10 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('settings.app_license') }}</x-ui.heading>
                <p class="text-xs">{{ __('settings.view_app_license') }}</p>
            </div>
        </div>

        <x-ui.separator/>

        <a href="{{ route('settings:licenses') }}" wire:navigate class="flex items-center gap-5 m-3 active:opacity-70 transition-opacity cursor-pointer">
            <x-lucide-library class="p-2.5 w-10 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('settings.libraries_licenses') }}</x-ui.heading>
                <p class="text-xs">{{ __('settings.view_libraries_licenses') }}</p>
            </div>
        </a>
    </x-ui.card>
</div>
