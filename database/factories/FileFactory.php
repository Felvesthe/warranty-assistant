<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\File;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'type' => $this->faker->mimeType(),
            'path' => $this->faker->filePath(),
            'item_id' => Item::factory(),
        ];
    }
}
