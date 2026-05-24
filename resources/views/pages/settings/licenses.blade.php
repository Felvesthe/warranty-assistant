<?php

use Livewire\Component;
use Illuminate\Support\Facades\File;

new class extends Component {
    public array $packages = [];

    public function mount(): void
    {
        $path = base_path('vendor/composer/installed.json');

        if (File::exists($path)) {
            $this->packages = collect(json_decode(File::get($path), true)['packages'] ?? [])
                ->map(function ($pkg): array {
                    return [
                        'name' => $pkg['name'] ?? __('settings.unknown'),
                        'version' => $pkg['version'] ?? __('settings.unknown'),
                        'licenses' => implode(', ', $pkg['license'] ?? [__('settings.unknown')]),
                    ];
                })
                ->sortBy('name')
                ->values()
                ->toArray();
        }
    }
};
?>

<div>
    <div class="flex items-center gap-4">
        <a href="{{ route('settings:index') }}" wire:navigate>
            <x-lucide-chevron-left class="w-6"/>
        </a>
        <x-page-heading>
            {{ __('settings.libraries_licenses') }}
        </x-page-heading>
    </div>

    <div class="mb-6 space-y-3">
        @foreach($packages as $pkg)
            <x-ui.card class="p-4 shadow-sm flex flex-col gap-1">
                <div class="flex justify-between items-center gap-4">
                    <span class="font-bold text-sm text-indigo-600 dark:text-indigo-400 wrap-break-word">{{ $pkg['name'] }}</span>
                    <x-ui.badge variant="outline" color="indigo" class="text-xs">{{ $pkg['version'] }}</x-ui.badge>
                </div>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                    {{ __('License') }}: <span class="font-semibold">{{ $pkg['licenses'] }}</span>
                </p>
            </x-ui.card>
        @endforeach
    </div>
</div>
