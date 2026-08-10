<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Providers;

use Gildsmith\Auth\Facades\UserFacade;
use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Contract\User\CustomerInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Providers\Concerns\BuildsPackagePaths;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    use BuildsPackagePaths;

    public function register(): void
    {
        // Facades
        $this->app->bind(UserFacadeInterface::class, fn () => new UserFacade);

        // Models
        $this->app->bind(UserInterface::class, User::class);
        $this->app->bind(CustomerInterface::class, Customer::class);
        $this->app->bind(EmployeeInterface::class, Employee::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        $this->loadRoutesFrom($this->packagePath('routes/api.php'));
    }
}
