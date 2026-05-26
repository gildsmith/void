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
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\SanctumServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SanctumServiceProvider::class);

        $this->app->bind(UserFacadeInterface::class, UserFacade::class);

        $this->app->bind(UserInterface::class, User::class);
        $this->app->bind(CustomerInterface::class, Customer::class);
        $this->app->bind(EmployeeInterface::class, Employee::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        $this->loadMigrationsFrom($this->packagePath('vendor/laravel/sanctum/database/migrations'));
        $this->loadRoutesFrom($this->packagePath('routes/api.php'));
    }

    /**
     * Helper function to build paths from the package root.
     */
    private function packagePath(string $path): string
    {
        return dirname(__DIR__, 2).'/'.$path;
    }
}
