<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Database\Factories;

use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
        ];
    }
}
