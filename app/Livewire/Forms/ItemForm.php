<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Actions\CreateItem;
use App\Actions\EditItem;
use App\Enums\Category;
use App\Enums\Warranty;
use App\Models\Item;
use Carbon\CarbonInterface;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
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
    public ?Item $item = null;

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
    public ?string $proofOfPurchase = '';

    public function setItem(Item $item): void
    {
        $this->item = $item;

        /** @var Category $category */
        $category = $item->category;

        /** @var CarbonInterface $dateOfPurchase */
        $dateOfPurchase = $item->date_of_purchase;

        /** @var Warranty $warranty */
        $warranty = $item->warranty_period;

        $this->name = $item->name;
        $this->price = (string) $item->price;
        $this->category = $category;
        $this->dateOfPurchase = $dateOfPurchase->format('d/m/Y');
        $this->warranty = $warranty;
        $this->serialNumber = $item->serial_number;
        $this->notes = $item->notes;

        $this->proofOfPurchase = $item->file?->path;
    }

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

    /**
     * @throws Throwable
     */
    public function update(): void
    {
        if ($this->item === null) {
            throw new InvalidArgumentException;
        }

        /** @var ValidatedData $validated */
        $validated = $this->validate();

        app(EditItem::class)
            ->execute($this->item, $validated);

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
