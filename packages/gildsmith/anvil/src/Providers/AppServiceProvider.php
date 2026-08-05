<?php

declare(strict_types=1);

namespace Gildsmith\Anvil\Providers;

use Gildsmith\Anvil\Commands\CreatePackageCommand;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    private static array $commands = [
        CreatePackageCommand::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(self::$commands);
        }
    }
}
