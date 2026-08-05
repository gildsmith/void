<?php

declare(strict_types=1);

namespace Gildsmith\Skeleton\Providers;

use Gildsmith\Support\Providers\Concerns\BuildsPackagePaths;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    use BuildsPackagePaths;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        $this->loadRoutesFrom($this->packagePath('routes/api.php'));
    }
}
