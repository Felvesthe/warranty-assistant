<?php

declare(strict_types=1);

use App\Models\Item;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function itemsWarrantyExpiring(): Collection
    {
        $startDate = now()->setTime(0, 0);
        $endDate = now()->addDays(30)->setTime(23, 59, 59);

        return Item::query()
            ->whereBetween('warranty_expiration_date', [$startDate, $endDate])
            ->orderBy('warranty_expiration_date')
            ->take(3)
            ->get();
    }
};
?>

<div>
    <x-page-heading>{{ __('dashboard.welcome') }} 👋</x-page-heading>

    <livewire:dashboard.stats/>

    <div class="flex justify-between items-center">
        <x-dashboard.section-header
            :title="__('dashboard.expiring_soon')"
            icon="calendar-clock"
        />

        <x-ui.link :href="route('items:index')" variant="soft">
            <div class="flex items-center gap-1">
                <span>{{ __('All') }}</span>
                <x-lucide-arrow-right class="w-5"/>
            </div>
        </x-ui.link>
    </div>

    @if ($this->itemsWarrantyExpiring->isEmpty())
        <x-ui.empty>
            <x-ui.empty.media>
                <x-lucide-sparkles class="w-10"/>
            </x-ui.empty.media>

            <x-ui.empty.contents>
                <x-ui.heading>{{ __('dashboard.nothing_here') }}</x-ui.heading>
                <x-ui.text class="opacity-70">
                    {{ __('dashboard.equipment_is_safe') }}
                </x-ui.text>
            </x-ui.empty.contents>
        </x-ui.empty>
    @else
        <div class="space-y-3">
            @foreach ($this->itemsWarrantyExpiring as $item)
                <livewire:item-card :$item :showDate="true" />
            @endforeach
        </div>
    @endif
</div>
