<?php

declare(strict_types=1);

namespace Gildsmith\Product\Database\Factories;

use Gildsmith\Product\Models\ProductCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCollectionFactory extends Factory
{
    protected $model = ProductCollection::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[a-z0-9_]{8}'),
            'type' => strtolower($this->faker->word()),
            'name' => [
                'en' => ucfirst($this->faker->word),
                'pl' => ucfirst($this->faker->word),
            ],
        ];
    }
}
