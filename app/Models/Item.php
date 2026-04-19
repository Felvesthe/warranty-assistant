<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsPrice;
use App\Enums\Category;
use App\Enums\Warranty;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(
    'name',
    'category',
    'date_of_purchase',
    'warranty_period',
    'price',
    'serial_number',
    'notes',
)]
class Item extends Model
{
    /** @use HasFactory<ItemFactory> */
    use HasFactory;

    /** @return HasOne<File, $this> */
    public function file(): HasOne
    {
        return $this->hasOne(File::class);
    }

    /** @return HasMany<Service, $this> */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /** @return Attribute<int, never> */
    public function daysOfWarranty(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calculateDaysOfWarrantyLeft()
        )->shouldCache();
    }

    private function calculateDaysOfWarrantyLeft(): int
    {
        /** @var CarbonImmutable $dateOfPurchase */
        $dateOfPurchase = $this->date_of_purchase;

        /** @var Warranty $warrantyPeriod */
        $warrantyPeriod = $this->warranty_period;
        $endDate = $dateOfPurchase->addMonths($warrantyPeriod->value);

        return Carbon::now()
            ->daysUntil($endDate)
            ->count();
    }

    protected function casts(): array
    {
        return [
            'category' => Category::class,
            'date_of_purchase' => 'date:d/m/Y',
            'warranty_period' => Warranty::class,
            'price' => AsPrice::class,
        ];
    }
}
