<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Product\AttributeFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin AttributeFacadeInterface
 */
class Attribute extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AttributeFacadeInterface::class;
    }
}
