<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Item;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'date' => $this->faker->date(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100_000),
        ];
    }
}
