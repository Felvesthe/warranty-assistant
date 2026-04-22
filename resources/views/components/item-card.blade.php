<?php

use Livewire\Component;
use App\Enums\Warranty;

new class extends Component
{
    public function getColor(int $warrantyLength): string
    {
        return match (true) {
            $warrantyLength <= 7 => 'red',
            $warrantyLength <= 30 => 'yellow',
            default => 'green',
        };
    }

    public function daysLeft(int $warrantyLength): string
    {
        return $warrantyLength . ' ' . __($warrantyLength === 1 ? 'Day' : 'Days');
    }
};
?>

@props([
    'item',
    'showDate' => false,
])

<x-ui.card class="flex justify-between items-center shadow" :wire:key="$item->id">
    <div class="flex items-center gap-3">
        <x-dynamic-component
            :component="'lucide-' . $item->category->icon()"
            class="p-2 w-8 bg-primary text-primary-fg rounded-full"
        />
        <div>
            <p class="text-sm font-bold">{{ $item->name }}</p>
            <p class="text-xs">{{ $item->price->dollar }}</p>
        </div>
    </div>
    <div class="flex justify-center items-end flex-col gap-1.5">
        <x-ui.badge variant="outline" :color="$this->getColor($item->days_of_warranty)" size="sm" class="uppercase" pill>
            @if ($item->warranty_period === Warranty::None || $item->warranty_period === Warranty::Lifetime)
                {{ $item->warranty_period->label() }}
            @elseif ($item->days_of_warranty >= 0)
                {{ $this->daysLeft($item->days_of_warranty) }}
            @else
                {{ __('warranties.none') }}
            @endif
        </x-ui.badge>

        @if ($showDate)
            <p class="text-xs font-semibold">{{ __('to') }} {{ $item->warranty_expiration_date->format('d/m/Y') }}</p>
        @endif
    </div>
</x-ui.card>
