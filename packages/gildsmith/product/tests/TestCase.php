<?php

declare(strict_types=1);

namespace Tests;

use Gildsmith\Contract\Facades\ProductFacadeInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement('PRAGMA foreign_keys=ON');
        app()->alias(ProductFacadeInterface::class, 'Gildsmith\Contract\Facades\Product');
    }
}
