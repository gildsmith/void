<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Providers;

use Gildsmith\Auth\Facades\UserFacade;
use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\Session;
use Gildsmith\Auth\Models\User;
use Gildsmith\Auth\Support\SessionManager;
use Gildsmith\Contract\Auth\Facades\UserFacadeInterface;
use Gildsmith\Contract\Auth\SessionInterface;
use Gildsmith\Contract\User\CustomerInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Providers\Concerns\BuildsPackagePaths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as LaravelAuth;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    use BuildsPackagePaths;

    public function register(): void
    {
        // Facades
        $this->app->bind(UserFacadeInterface::class, fn() => new UserFacade());

        // Models
        $this->app->bind(UserInterface::class, User::class);
        $this->app->bind(CustomerInterface::class, Customer::class);
        $this->app->bind(EmployeeInterface::class, Employee::class);
        $this->app->bind(SessionInterface::class, Session::class);

        $this->app->singleton(SessionManager::class);

        $this->configureAuthProvider();
        $this->configureAuthGuard();
    }

    public function boot(): void
    {
        LaravelAuth::viaRequest('gildsmith-token', function (Request $request) {
            return $this->app->make(SessionManager::class)->userFromRequest($request);
        });

        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        $this->loadRoutesFrom($this->packagePath('routes/api.php'));
    }

    private function configureAuthProvider(): void
    {
        $configuredModel = config('auth.providers.users.model');

        if ($configuredModel !== null && $configuredModel !== 'App\\Models\\User') {
            return;
        }

        config()->set('auth.providers.users.model', User::class);
    }

    private function configureAuthGuard(): void
    {
        config()->set('auth.guards.gildsmith', [
            'driver' => 'gildsmith-token',
            'provider' => 'users',
        ]);
    }
}
