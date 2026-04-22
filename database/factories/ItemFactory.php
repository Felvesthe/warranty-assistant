<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Category;
use App\Enums\Warranty;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'category' => $this->faker->randomElement(Category::cases()),
            'date_of_purchase' => $this->faker->dateTimeBetween('-5 years'),
            'warranty_period' => $this->faker->randomElement(Warranty::cases()),
            'warranty_expiration_date' => $this->faker->dateTimeBetween('-3 years'),
            'price' => $this->faker->numberBetween(100_000),
            'serial_number' => $this->faker->md5(),
            'notes' => $this->faker->paragraph(),
        ];
    }
}
