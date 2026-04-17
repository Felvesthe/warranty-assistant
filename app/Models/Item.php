<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsPrice;
use App\Enums\Category;
use App\Enums\Warranty;
use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
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
