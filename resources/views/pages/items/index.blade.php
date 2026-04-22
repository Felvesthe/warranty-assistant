<?php

use App\Enums\Category;
use App\Enums\Warranty;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $search = '';
    public ?Category $selectedCategory = null;

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        return Item::query()
            ->whereLike('name', "%$this->search%")
            ->when($this->selectedCategory, function (Builder $query): void {
                $query->whereLike('category', $this->selectedCategory);
            })
            ->orderByDesc('created_at')
            ->paginate(config()->integer('pagination.per_page'));
    }

    public function changeCategory(?Category $category): void
    {
        $this->selectedCategory = $category;
    }

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

<div class="my-6">
    <x-ui.input
        wire:model.live.debounce="search"
        :placeholder="__('Search') . '...'"
        class="mb-3 max-w-full shadow"
        leftIcon="magnifying-glass"
    />

    <div class="flex items-center gap-1.5 overflow-x-scroll">
        <x-ui.badge
            size="lg"
            :color="is_null($selectedCategory) ? 'violet' : ''"
            wire:click="changeCategory(null)"
            pill
        >
            {{ __('All') }}
        </x-ui.badge>

        @foreach (Category::cases() as $category)
            <x-ui.badge
                size="lg"
                :color="$selectedCategory === $category ? 'violet' : ''"
                wire:click="changeCategory('{{ $category }}')"
                pill
            >
                {{ $category->label() }}
            </x-ui.badge>
        @endforeach
    </div>

    <div class="my-6 space-y-3">
        @if ($this->items->isEmpty())
            <x-ui.empty>
                <x-ui.empty.media>
                    <x-lucide-circle-alert class="w-10" />
                </x-ui.empty.media>

                <x-ui.empty.contents>
                    <x-ui.heading>{{ __('No results') }}</x-ui.heading>
                    <x-ui.text class="opacity-70">
                        {{ __('items.items_not_found') }}
                    </x-ui.text>

                    <x-ui.link :href="route('items:create')" class="mt-3">
                        {{ __('items.form.add_item') }}
                    </x-ui.link>
                </x-ui.empty.contents>
            </x-ui.empty>
        @else
            @foreach ($this->items as $item)
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
                    <x-ui.badge variant="outline" :color="$this->getColor($item->days_of_warranty)" size="sm" class="uppercase" pill>
                        @if ($item->warranty_period === Warranty::None || $item->warranty_period === Warranty::Lifetime)
                            {{ $item->warranty_period->label() }}
                        @elseif ($item->days_of_warranty >= 0)
                            {{ $this->daysLeft($item->days_of_warranty) }}
                        @else
                            {{ __('warranties.none') }}
                        @endif
                    </x-ui.badge>
                </x-ui.card>
            @endforeach
        @endif
    </div>

    {{ $this->items->links() }}
</div>
