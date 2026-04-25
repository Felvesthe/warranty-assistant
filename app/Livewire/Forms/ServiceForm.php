<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Item;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ServiceForm extends Form
{
    public Item $item;

    #[Validate]
    public string $dateOfService = '';

    #[Validate]
    public string $description = '';

    #[Validate]
    public ?string $price = '';

    public function setItem(Item $item): void
    {
        $this->item = $item;
    }

    public function store(): void
    {
        $this->validate();

        $convertedPrice = $this->price !== null ? (float) str_replace([' ', ','], ['', '.'], $this->price) : null;
        $serviceDate = CarbonImmutable::createFromFormat('d/m/Y', $this->dateOfService);

        $this->item->services()->create([
            'date' => $serviceDate,
            'description' => $this->description,
            'price' => $convertedPrice,
        ]);

        $this->resetExcept('item');
    }

    /** @return array<string, mixed[]> */
    protected function rules(): array
    {
        return [
            'dateOfService' => ['required', Rule::date()->format('d/m/Y')->afterOrEqual($this->item->date_of_purchase)],
            'description' => ['required', 'string', 'max:500'],
            'price' => ['nullable'],
        ];
    }

    /** @return array<string, string> */
    protected function validationAttributes(): array
    {
        return [
            'dateOfService' => __('validation.attributes.date_of_service'),
        ];
    }
}
