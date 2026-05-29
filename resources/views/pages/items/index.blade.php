<?php

use App\Enums\Category;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $search = '';
    public ?Category $selectedCategory = null;
    public bool $showWithoutWarranty = true;

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        return Item::query()
            ->whereLike('name', "%$this->search%")
            ->when($this->selectedCategory, function (Builder $query): void {
                $query->whereLike('category', $this->selectedCategory);
            })
            ->when(! $this->showWithoutWarranty, function (Builder $query) {
                $query->whereDate('warranty_expiration_date', '>=', now());
            })
            ->orderByDesc('created_at')
            ->paginate(config()->integer('pagination.per_page'));
    }

    public function changeCategory(?Category $category): void
    {
        $this->selectedCategory = $category;
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
            :color="is_null($selectedCategory) ? 'indigo' : ''"
            variant="solid"
            wire:click="changeCategory(null)"
            pill
        >
            {{ __('All') }}
        </x-ui.badge>

        @foreach (Category::cases() as $category)
            <x-ui.badge
                size="lg"
                :color="$selectedCategory === $category ? 'indigo' : ''"
                variant="solid"
                wire:click="changeCategory('{{ $category }}')"
                class="active:scale-95 transition"
                pill
            >
                {{ $category->label() }}
            </x-ui.badge>
        @endforeach
    </div>

    <div class="my-6">
        <x-ui.checkbox
            wire:model.live.debounce="showWithoutWarranty"
            :label="__('items.include_out_of_warranty')"
        />
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
                <x-item-card :$item />
            @endforeach
        @endif
    </div>

    {{ $this->items->links() }}
</div>
