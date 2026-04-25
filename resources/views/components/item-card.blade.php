@props([
    'item',
    'showDate' => false,
])

<a href="{{ route('items:show', $item) }}" class="block">
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
            <livewire:warranty-badge :$item />

            @if ($showDate)
                <p class="text-xs font-semibold">{{ __('to') }} {{ $item->warranty_expiration_date->format('d/m/Y') }}</p>
            @endif
        </div>
    </x-ui.card>
</a>
