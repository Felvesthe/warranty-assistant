<?php

declare(strict_types=1);

use App\Models\Item;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <div class="grid grid-cols-2 gap-2">
        <x-dashboard.card
            icon="calculator"
            iconTextColor="text-blue-600"
            iconBgColor="bg-blue-100"
            :title="__('dashboard.equipment_value')"
            value="0,00 PLN"
        />

        <x-dashboard.card
            icon="shield-alert"
            iconTextColor="text-green-600"
            iconBgColor="bg-green-100"
            :title="__('dashboard.active_warranties')"
            value="0 / 0"
        />
    </div>
</div>
