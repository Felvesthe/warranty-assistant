<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\Category;
use App\Enums\Warranty;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Throwable;

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
        $this->validate();

        DB::transaction(function (): void {
            $convertedPrice = (float) str_replace([' ', ','], ['', '.'], $this->price);
            $purchaseDate = Carbon::createFromFormat('d/m/Y', $this->dateOfPurchase);

            $item = Item::create([
                'name' => $this->name,
                'price' => $convertedPrice,
                'category' => $this->category,
                'date_of_purchase' => $purchaseDate,
                'warranty_period' => $this->warranty,
                'serial_number' => $this->serialNumber,
                'notes' => $this->notes,
            ]);

            $item->file()->create([
                'type' => Storage::mimeType($this->proofOfPurchase),
                'path' => $this->proofOfPurchase,
            ]);
        });

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
