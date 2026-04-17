<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\Price;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/** @implements CastsAttributes<?Price, ?int> */
class AsPrice implements CastsAttributes
{
    /**
     * @param  ?int  $value
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Price
    {
        if (is_null($value)) {
            return null;
        }

        return Price::fromCent($value);
    }

    /**
     * @param  float | Price | null  $value
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Price) {
            return $value->cent;
        }

        return Price::fromDollar((float) $value)->cent;
    }
}
