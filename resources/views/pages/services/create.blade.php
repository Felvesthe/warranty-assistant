<?php

use App\Livewire\Forms\ServiceForm;
use App\Models\Item;
use Livewire\Component;
use Illuminate\Support\Str;
use Native\Mobile\Facades\Dialog;

new class extends Component {
    public Item $item;
    public ServiceForm $form;

    public function mount(): void
    {
        $this->form->setItem($this->item);
    }

    public function save(): void
    {
        $this->form->store();

        $this->redirectRoute('items:services:index', $this->item);

        Dialog::toast(__('services.toasts.add_entry_success'));
    }
};
?>

<div>
    <div class="flex items-center gap-3 my-4">
        <a href="{{ route('items:services:index', $item) }}" class="px-1 active:opacity-60 transition-opacity">
            <x-lucide-chevron-left class="w-6"/>
        </a>

        <x-ui.heading level="h1" size="md">{{ __('services.form.new_entry') }}</x-ui.heading>
    </div>

    <div class="mb-4 text-base">
        <p>{{ __('services.form.creating_entry_for') }}:</p>
        <p class="font-bold">{{ Str::limit($item->name, 24) }}</p>
    </div>

    <form wire:submit="save" class="flex flex-col gap-5">
        @csrf

        <x-ui.field required>
            <x-ui.label
                class="text-xs uppercase">{{ Str::ucfirst(__('validation.attributes.date_of_service')) }}</x-ui.label>
            <x-ui.input
                wire:model.live.debounce="form.dateOfService"
                x-mask="99/99/9999"
                inputmode="numeric"
                :placeholder="__('items.form.date_placeholder')"
                leftIcon="calendar"
            />
            <x-ui.error name="form.dateOfService"/>
        </x-ui.field>

        <x-ui.field required>
            <x-ui.label class="text-xs uppercase">{{ __('validation.attributes.description') }}</x-ui.label>
            <x-ui.textarea
                wire:model.live.debounce="form.description"
                :placeholder="__('services.form.description_placeholder')"
                resize="none"
                class="text-sm"
            />
            <x-ui.error name="form.description"/>
        </x-ui.field>

        <x-ui.field>
            <x-ui.label class="text-xs uppercase">{{ Str::ucfirst(__('validation.attributes.price')) }}</x-ui.label>
            <x-ui.input
                wire:model.live.debounce="form.price"
                x-mask:dynamic="$money($input, ',', ' ')"
                inputmode="decimal"
                placeholder="0,00"
                leftIcon="currency-dollar"
            />
            <x-ui.error name="form.price"/>
        </x-ui.field>

        <x-ui.button type="submit" size="lg" icon="plus-circle" color="indigo" class="col-span-full shadow">
            {{ __('Save') }}
        </x-ui.button>
    </form>
</div>
