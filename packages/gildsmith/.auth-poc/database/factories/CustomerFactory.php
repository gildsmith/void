<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Database\Factories;

use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
        ];
    }
}
