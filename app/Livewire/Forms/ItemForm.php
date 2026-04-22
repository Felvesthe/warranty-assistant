<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Actions\CreateItem;
use App\Enums\Category;
use App\Enums\Warranty;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Throwable;

/**
 * @phpstan-type ValidatedData array{
 *     name: string,
 *     price: string,
 *     category: Category,
 *     dateOfPurchase: string,
 *     warranty: Warranty,
 *     serialNumber: ?string,
 *     notes: ?string,
 *     proofOfPurchase: string
 * }
 */
class ItemForm extends Form
{
    #[Validate]
    public string $name = '';

    #[Validate]
    public string $price = '';

    #[Validate]
    public ?Category $category = null;

    #[Validate]
    public string $dateOfPurchase = '';

    #[Validate]
    public ?Warranty $warranty = null;

    #[Validate]
    public ?string $serialNumber = null;

    #[Validate]
    public ?string $notes = null;

    #[Validate]
    public string $proofOfPurchase = '';

    /**
     * @throws Throwable
     */
    public function store(): void
    {
        /** @var ValidatedData $validated */
        $validated = $this->validate();

        app(CreateItem::class)
            ->execute($validated);

        $this->reset();
    }

    /** @return array<string, mixed[]> */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'price' => ['required'],
            'category' => ['required', Rule::enum(Category::class)],
            'dateOfPurchase' => ['required', 'date_format:d/m/Y'],
            'warranty' => ['required', Rule::enum(Warranty::class)],
            'serialNumber' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
            'proofOfPurchase' => ['required'],
        ];
    }

    /** @return array<string, string> */
    protected function validationAttributes(): array
    {
        return [
            'dateOfPurchase' => __('validation.attributes.date_of_purchase'),
            'serialNumber' => __('validation.attributes.serial_number'),
            'proofOfPurchase' => __('validation.attributes.proof_of_purchase'),
        ];
    }
}
