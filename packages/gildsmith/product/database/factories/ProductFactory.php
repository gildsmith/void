<?php

declare(strict_types=1);

namespace Gildsmith\Product\Database\Factories;

use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[a-z0-9_]{8}'),
            'blueprint_id' => Blueprint::factory(),
            'name' => [
                'en' => ucfirst($this->faker->word),
                'pl' => ucfirst($this->faker->word),
            ],
        ];
    }

    public function trashed(): self
    {
        return $this->state(fn () => [
            'deleted_at' => Carbon::now(),
        ]);
    }
}
