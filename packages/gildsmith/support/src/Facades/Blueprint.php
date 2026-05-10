<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Product\BlueprintFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin BlueprintFacadeInterface
 */
class Blueprint extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BlueprintFacadeInterface::class;
    }
}
