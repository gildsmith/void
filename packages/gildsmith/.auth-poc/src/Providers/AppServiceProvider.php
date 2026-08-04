<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Providers;

use Gildsmith\Auth\Facades\UserFacade;
use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Gildsmith\Auth\Policies\Product\AttributePolicy;
use Gildsmith\Auth\Policies\Product\AttributeValuePolicy;
use Gildsmith\Auth\Policies\Product\BlueprintPolicy;
use Gildsmith\Auth\Policies\Product\ProductCollectionPolicy;
use Gildsmith\Auth\Policies\Product\ProductPolicy;
use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Contract\User\CustomerInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(AttributeInterface::class, AttributePolicy::class);
        Gate::policy(AttributeValueInterface::class, AttributeValuePolicy::class);
        Gate::policy(BlueprintInterface::class, BlueprintPolicy::class);
        Gate::policy(ProductCollectionInterface::class, ProductCollectionPolicy::class);
        Gate::policy(ProductInterface::class, ProductPolicy::class);

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
