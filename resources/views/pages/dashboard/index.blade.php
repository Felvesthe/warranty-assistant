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

    <x-dashboard.empty-card />
</div>
