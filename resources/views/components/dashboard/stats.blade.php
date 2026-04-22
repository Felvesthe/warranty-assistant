<?php

declare(strict_types=1);

use App\Models\Item;
use App\ValueObjects\Price;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Carbon\Carbon;

new class extends Component {
    #[Computed]
    public function totalValue(): int
    {
        return Item::sum('price');
    }

    #[Computed]
    public function totalItemsCount(): int
    {
        return Item::count();
    }

    #[Computed]
    public function activeWarrantyItemsCount(): int
    {
        return Item::query()
            ->whereDate('warranty_expiration_date', '>=', Carbon::now()->setTime(23, 59, 59))
            ->count();
    }
};
?>

<div>
    <div class="grid grid-cols-2 gap-2">
        <x-dashboard.card
            icon="calculator"
            iconTextColor="text-blue-600"
            iconBgColor="bg-blue-100"
            :title="__('dashboard.equipment_value')"
            :value="Price::fromCent($this->totalValue)->formatted"
        />

        <x-dashboard.card
            icon="shield-alert"
            iconTextColor="text-green-600"
            iconBgColor="bg-green-100"
            :title="__('dashboard.active_warranties')"
            :value="$this->activeWarrantyItemsCount . ' / ' . $this->totalItemsCount"
        />
    </div>
</div>
