<?php

declare(strict_types=1);

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <x-page-heading>{{ __('dashboard.welcome') }} 👋</x-page-heading>

    <livewire:dashboard.stats />

    <div class="flex justify-between items-center">
        <x-dashboard.section-header
            :title="__('dashboard.expiring_30_days')"
            icon="calendar-clock"
        />

        <x-ui.link href="/items" variant="soft">
            <div class="flex items-center gap-1">
                <span>{{ __('All') }}</span>
                <x-lucide-arrow-right class="w-5" />
            </div>
        </x-ui.link>
    </div>

    <x-ui.empty>
        <x-ui.empty.media>
            <x-lucide-sparkles class="w-10" />
        </x-ui.empty.media>

        <x-ui.empty.contents>
            <x-ui.heading>{{ __('dashboard.nothing_here') }}</x-ui.heading>
            <x-ui.text class="opacity-70">
                {{ __('dashboard.equipment_is_safe') }}
            </x-ui.text>
        </x-ui.empty.contents>
    </x-ui.empty>
</div>
