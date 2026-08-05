<?php

declare(strict_types=1);

namespace Tests;

use Gildsmith\Anvil\Providers\AppServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            AppServiceProvider::class,
        ];
    }
}
