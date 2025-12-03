<?php

declare(strict_types=1);

namespace Gildsmith\Product\Database\Factories;

use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeValueFactory extends Factory
{
    protected $model = AttributeValue::class;

    public function definition(): array
    {
        return [
            'attribute_id' => Attribute::factory(),
            'code' => $this->faker->unique()->regexify('[a-z0-9_]{8}'),
            'name' => [
                'en' => ucfirst($this->faker->word),
                'pl' => ucfirst($this->faker->word),
            ],
        ];
    }
}
