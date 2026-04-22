<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Category;
use App\Enums\Warranty;
use App\Models\Item;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Throwable;

/**
 * @phpstan-type ItemData array{
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
final class CreateItem
{
    /**
     * @param  ItemData  $data
     *
     * @throws Throwable
     */
    public function execute(array $data): Item
    {
        return DB::transaction(function () use ($data): Item {
            $convertedPrice = (float) str_replace([' ', ','], ['', '.'], $data['price']);
            $purchaseDate = CarbonImmutable::createFromFormat('d/m/Y', $data['dateOfPurchase']);

            if ($purchaseDate === null) {
                throw new InvalidArgumentException;
            }

            $warrantyExpirationDate = $purchaseDate->addMonths($data['warranty']->value);

            $item = Item::create([
                'name' => $data['name'],
                'price' => $convertedPrice,
                'category' => $data['category'],
                'date_of_purchase' => $purchaseDate,
                'warranty_period' => $data['warranty'],
                'warranty_expiration_date' => $warrantyExpirationDate,
                'serial_number' => $data['serialNumber'],
                'notes' => $data['notes'],
            ]);

            $item->file()->create([
                'type' => Storage::mimeType($data['proofOfPurchase']),
                'path' => $data['proofOfPurchase'],
            ]);

            return $item;
        });
    }
}
