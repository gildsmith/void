<?php

declare(strict_types=1);

namespace Gildsmith\Permission\Providers;

use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database'.DIRECTORY_SEPARATOR.'migrations'));
        $this->loadRoutesFrom($this->packagePath('routes'.DIRECTORY_SEPARATOR.'api.php'));
    }

    /**
     * Helper function to build paths from the package root.
     */
    private function packagePath(string $path): string
    {
        return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.$path;
    }
}
