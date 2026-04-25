<?php

use App\Enums\Warranty;
use Livewire\Component;

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
    'withText' => false,
])

<div>
    <x-ui.badge variant="outline" :color="$this->getColor($item->days_of_warranty)" size="sm" {{ $attributes->merge(['class' => 'uppercase']) }} pill>
        @if ($item->warranty_period === Warranty::None || $item->warranty_period === Warranty::Lifetime)
            {{ $item->warranty_period->label() }}
        @elseif ($item->days_of_warranty >= 0)
            @if ($withText)
                {{ __('items.valid_warranty') }} ({{ $this->daysLeft($item->days_of_warranty) }})
            @else
                {{ $this->daysLeft($item->days_of_warranty) }}
            @endif
        @else
            {{ __('warranties.none') }}
        @endif
    </x-ui.badge>
</div>
