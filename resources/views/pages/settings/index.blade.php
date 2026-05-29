<?php

use App\Enums\Currency;
use App\Settings\UserSettings;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;

new class extends Component {
    public Currency $currency;

    public function mount(UserSettings $userSettings): void
    {
        $this->currency = $userSettings->currency;
    }

    public function updatedCurrency(UserSettings $userSettings): void
    {
        $userSettings->currency = $this->currency;
        $userSettings->save();
    }

    #[On('persist-theme-change')]
    public function saveTheme(string $theme, UserSettings $userSettings): void
    {
        $userSettings->theme = $theme;
        $userSettings->save();
    }
}
?>

<div>
    <x-page-heading>
        {{ __('actions.settings') }}
    </x-page-heading>

    <div class="mb-6">
        <x-ui.heading level="h2" size="xs" class="mb-1 uppercase font-bold">{{ __('Theme') }}</x-ui.heading>
        <x-ui.theme-switcher variant="stacked" systemIcon="device-phone-mobile"/>
    </div>

    <x-ui.heading level="h2" size="xs" class="mb-1 uppercase font-bold">{{ __('Currency') }}</x-ui.heading>
    <x-ui.card class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4 flex-2/3">
            <x-lucide-dollar-sign class="p-2.5 w-10 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
            <div class="text-left">
                <x-ui.heading level="h2">{{ __('settings.default_currency') }}</x-ui.heading>
                <p class="text-xs">{{ __('settings.for_item_value') }}</p>
            </div>
        </div>

        <x-select
            wire:model.live.debounce="currency" class="flex-1/3"
            :placeholder="__('Currency')"
        >
            @foreach (Currency::cases() as $key => $currency)
                <option value="{{ $currency->value }}" wire:key="{{ $key }}">
                    {{ Str::upper($currency->value) }}
                </option>
            @endforeach
        </x-select>
    </x-ui.card>

    <livewire:settings.application-info />
</div>
