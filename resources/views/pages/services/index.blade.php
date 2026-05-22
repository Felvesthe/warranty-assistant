<?php

use App\Models\Item;
use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Alert\ButtonPressed;
use Native\Mobile\Facades\Dialog;

new class extends Component {
    public Item $item;

    public ?Service $selectedService = null;

    #[Computed]
    public function services(): LengthAwarePaginator
    {
        return $this->item
            ->services()
            ->orderByDesc('date')
            ->paginate(config()->integer('pagination.services_per_page'));
    }

    public function openDeleteDialog(Service $service): void
    {
        $this->selectedService = $service;

        Dialog::alert(
            __('Confirm'),
            __('Are you sure you want to delete this service entry? This action cannot be undone.'),
            [__('Cancel'), __('Delete')]
        )
            ->id('delete-confirm')
            ->show();
    }

    #[OnNative(ButtonPressed::class)]
    public function handleButton(int $index, string $label, ?string $id = null): void
    {
        if ($id === 'delete-confirm' && $label === __('Delete')) {
            $this->deleteService();
        }
    }

    private function deleteService(): void
    {
        if ($this->selectedService->delete()) {
            Dialog::toast(__('Deleted successfully'));
        }

        $this->selectedService = null;
    }
};
?>

<div>
    <div class="flex justify-between items-center my-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('items:show', $item) }}" class="px-1 active:opacity-60 transition-opacity">
                <x-lucide-chevron-left class="w-6"/>
            </a>

            <x-ui.heading level="h1" size="md">{{ __('services.service_history') }}</x-ui.heading>
        </div>

        <a href="{{ route('items:services:create', $item) }}" class="mx-1 p-1 text-indigo-600 bg-indigo-600/10 dark:text-indigo-400 dark:bg-indigo-400/15 rounded-full active:opacity-70 transition-opacity">
            <x-lucide-plus class="w-6"/>
        </a>
    </div>

    <div class="mb-4 text-base">
        <p>{{ __('items.item_name') }}:</p>
        <p class="font-bold">{{ $item->name }}</p>
    </div>

    @if ($this->services->count() > 0)
        <div class="space-y-3">
            @foreach ($this->services as $service)
                <x-ui.card :wire:key="$service->id" class="shadow">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-1.5 text-sm">
                            <x-lucide-calendar class="w-5"/>
                            {{ $service->date->format('d/m/Y') }}
                        </div>

                        <button wire:click="openDeleteDialog({{ $service }})"
                                class="p-2 text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900/30 rounded-full active:opacity-70 transition-opacity">
                            <x-lucide-trash class="w-5"/>
                        </button>
                    </div>

                    <x-ui.separator class="my-3"/>

                    <p class="text-sm text-justify wrap-anywhere">
                        {{ $service->description }}
                    </p>

                    @if ($service->price !== null && $service->price->cent > 0)
                        <x-ui.separator class="my-3"/>

                        <p class="flex items-center gap-1.5 text-sm font-bold">
                            <x-lucide-dollar-sign class="w-5"/>
                            {{ $service->price->formatted }}
                        </p>
                    @endif
                </x-ui.card>
            @endforeach

            {{ $this->services->links() }}
        </div>
    @else
        <x-ui.empty>
            <x-ui.empty.media>
                <x-lucide-wrench class="w-10"/>
            </x-ui.empty.media>

            <x-ui.empty.contents>
                <x-ui.heading>{{ __('services.empty.no_service_entries') }}</x-ui.heading>
                <x-ui.text class="text-center opacity-70">
                    {{ __('services.empty.add_first_entry_info') }}
                </x-ui.text>

                <x-ui.link :href="route('items:services:create', $item)" class="mt-3">
                    {{ __('services.empty.add_entry') }}
                </x-ui.link>
            </x-ui.empty.contents>
        </x-ui.empty>
    @endif
</div>
