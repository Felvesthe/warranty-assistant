<?php

use App\Actions\DeleteItem;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Alert\ButtonPressed;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\Share;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    public Item $item;

    public function shareItemInfo(): void
    {
        Share::file(
            title: 'Hello',
            text: $this->buildShareMessage(),
            filePath: Storage::path($this->item->file->path),
        );
    }

    public function openEditForm(): void
    {
        $this->redirectRoute('items:edit', ['item' => $this->item]);
    }

    public function openDeleteDialog(): void
    {
        Dialog::alert(
            __('Confirm'),
            __('Are you sure you want to delete this item? This action cannot be undone.'),
            [__('Cancel'), __('Delete')]
        )
            ->id('delete-confirm')
            ->show();
    }

    #[OnNative(ButtonPressed::class)]
    public function handleButton(int $index, string $label, ?string $id = null): void
    {
        if ($id === 'delete-confirm' && $label === __('Delete')) {
            $this->deleteItem();
        }
    }

    private function deleteItem(): void
    {
        app(DeleteItem::class)
            ->execute($this->item);

        $this->redirectRoute('items:index');

        Dialog::toast(__('Deleted successfully'));
    }

    private function buildShareMessage(): string
    {
        $message = __('items.item_name') . ': ' . $this->item->name . PHP_EOL;
        $message .= Str::ucfirst(__('validation.attributes.date_of_purchase')) . ': ' . $this->item->date_of_purchase->format('d/m/Y') . PHP_EOL;
        $message .= Str::ucfirst(__('validation.attributes.price')) . ': ' . $this->item->price->formatted . PHP_EOL;
        $message .= Str::ucfirst(__('validation.attributes.warranty')) . ': ' . $this->item->warranty_period->label() . PHP_EOL;

        if ($this->item->serial_number) {
            $message .= Str::ucfirst(__('validation.attributes.serial_number')) . ': ' . $this->item->serial_number . PHP_EOL;
        }

        if ($this->item->notes) {
            $message .= Str::ucfirst(__('validation.attributes.notes')) . ': ' . $this->item->notes . PHP_EOL;
        }

        return $message;
    }
};
?>

<div>
    <div class="flex justify-between items-center mt-4 mb-8">
        <button @click="history.back()" class="px-1">
            <x-lucide-chevron-left class="w-6"/>
        </button>

        <div class="flex items-center gap-5">
            <button wire:click="shareItemInfo" class="px-1">
                <x-lucide-share-2 class="w-6"/>
            </button>

            <button wire:click="openEditForm" class="px-1">
                <x-lucide-edit class="w-6"/>
            </button>

            <button wire:click="openDeleteDialog" class="px-1 text-red-700">
                <x-lucide-trash-2 class="w-6"/>
            </button>
        </div>
    </div>

    <div class="flex justify-center items-center flex-col">
        <x-dynamic-component
            :component="'lucide-' . $item->category->icon()"
            class="mb-3 p-4 w-16 bg-indigo-600/10 text-indigo-600 rounded-full"
        />

        <x-ui.heading level="h1" size="lg" class="font-bold">
            {{ $item->name }}
        </x-ui.heading>

        <x-ui.heading level="h2" size="xs" class="mb-3">
            {{ $item->category->label() }}
        </x-ui.heading>

        <livewire:warranty-badge class="mb-6" :$item :withText="true"/>
    </div>

    <button class="flex justify-between items-center mb-3 p-4 w-full bg-indigo-100 rounded-lg">
        <div class="flex justify-center items-center">
            <x-lucide-wrench class="p-2 w-10 bg-indigo-600/10 text-indigo-600 rounded-full"/>
            <div class="flex justify-center items-start flex-col ml-3">
                <p class="font-bold">{{ __('services.service_history') }}</p>
                <p class="text-sm">
                    @if ($item->services->count() > 0)
                        {{ __('services.number_of_entries') }}: {{ $item->services->count() }}
                    @else
                        {{ __('services.no_entries') }}
                    @endif
                </p>
            </div>
        </div>

        <x-lucide-chevron-right class="w-5"/>
    </button>

    <x-ui.card class="mb-3 shadow">
        <x-ui.heading level="h3" class="mb-3 uppercase">
            {{ __('Purchase details') }}
        </x-ui.heading>

        <div class="flex justify-between items-center">
            <div class="flex items-center gap-1.5">
                <x-lucide-calendar class="w-5"/>
                <p>{{ Str::ucfirst(__('validation.attributes.date_of_purchase')) }}</p>
            </div>

            <p class="font-bold">{{ $item->date_of_purchase->format('d/m/Y') }}</p>
        </div>

        <x-ui.separator class="my-3"/>

        <div class="flex justify-between items-center">
            <div class="flex items-center gap-1.5">
                <x-lucide-dollar-sign class="w-5"/>
                <p>{{ Str::ucfirst(__('validation.attributes.price')) }}</p>
            </div>

            <p class="font-bold">{{ $item->price->formatted }}</p>
        </div>

        <x-ui.separator class="my-3"/>

        <div class="flex justify-between items-center">
            <div class="flex items-center gap-1.5">
                <x-lucide-shield-check class="w-5"/>
                <p>{{ Str::ucfirst(__('validation.attributes.warranty')) }}</p>
            </div>

            <p class="font-bold">{{ $item->warranty_period->label() }}</p>
        </div>
    </x-ui.card>

    @if ($item->serial_number !== null)
        <x-ui.card class="mb-3 shadow">
            <x-ui.heading level="h3" class="mb-3 uppercase">
                {{ Str::title(__('validation.attributes.serial_number')) }}
            </x-ui.heading>

            <div class="text-justify">
                {{ $item->serial_number }}
            </div>
        </x-ui.card>
    @endif

    @if ($item->notes !== null)
        <x-ui.card class="mb-3 shadow">
            <x-ui.heading level="h3" class="mb-3 uppercase">
                {{ Str::title(__('validation.attributes.notes')) }}
            </x-ui.heading>

            <div class="wrap-anywhere">
                {{ $item->notes }}
            </div>
        </x-ui.card>
    @endif

    @if ($item->file !== null)
        <x-ui.heading level="h3" class="mb-1">
            {{ Str::ucfirst(__('validation.attributes.proof_of_purchase')) }}
        </x-ui.heading>

        <x-ui.card class="flex justify-center items-center p-0 max-h-64 rounded-lg overflow-hidden">
            <x-image-preview src="{{ Storage::url($item->file->path) }}"/>
        </x-ui.card>
    @endif
</div>
