<?php

declare(strict_types=1);

/** @noinspection PhpMultipleClassDeclarationsInspection */
uses(Tests\TestCase::class)->in('Access', 'Architecture', 'Unit', 'Feature');

/**
 * Silently binds a concrete implementation to an abstract
 * type in the dependency injection container.
 */
function bind(Closure|string $abstract, Closure|string $concrete): void
{
    try {
        app()->bind($abstract, $concrete);
    } catch (Throwable) {
        //
    }
}
