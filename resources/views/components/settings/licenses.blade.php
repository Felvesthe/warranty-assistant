<?php

use Livewire\Component;
use Native\Mobile\Facades\Dialog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    public function openLicenseDialog(?string $license = null): void
    {
        Dialog::alert(
            title: __(
                $license !== null ? 'License' : 'Licenses'
            ),
            message: $license !== null
                ? $this->getLicenseContent($license)
                : $this->getOtherLicenses(),
        );
    }

    public function getOtherLicenses(): string
    {
        $files = File::files(resource_path('licenses/others'));

        return collect($files)
            ->map(fn($file) => File::get($file))
            ->implode(PHP_EOL . '------------' . PHP_EOL . PHP_EOL);
    }

    private function getLicenseContent(string $license): string
    {
        $path = resource_path('licenses/' . $license . '.txt');

        if (!File::exists($path)) {
            throw new InvalidArgumentException;
        }

        return File::get($path);
    }
};
?>

<div {{ $attributes }}>
    <x-ui.heading level="h2" size="xs" class="mb-1 uppercase font-bold">{{ __('Licenses') }}</x-ui.heading>
    <x-ui.card class="p-0 text-sm shadow">
        <x-settings.license wire:click="openLicenseDialog('laravel')" icon="globe">
            <x-slot:heading>Laravel</x-slot:heading>
            <x-slot:text>Copyright (c) Taylor Otwell</x-slot:text>
        </x-settings.license>

        <x-ui.separator class="my-3"/>

        <x-settings.license wire:click="openLicenseDialog('nativephp')" icon="smartphone-charging">
            <x-slot:heading>NativePHP</x-slot:heading>
            <x-slot:text>Copyright (c) 2026 Bifrost Technology, LLC</x-slot:text>
        </x-settings.license>

        <x-ui.separator class="my-3"/>

        <x-settings.license wire:click="openLicenseDialog('lucide')" icon="image">
            <x-slot:heading>Lucide Icons</x-slot:heading>
            <x-slot:text>Copyright (c) 2026 Lucide Icons and Contributors</x-slot:text>
        </x-settings.license>

        <x-ui.separator class="my-3"/>

        <x-settings.license wire:click="openLicenseDialog(null)" icon="copyright">
            <x-slot:heading>{{ __('Others') }}</x-slot:heading>
            <x-slot:text>{{ __('Licenses for the software used') }}</x-slot:text>
        </x-settings.license>
    </x-ui.card>
</div>
