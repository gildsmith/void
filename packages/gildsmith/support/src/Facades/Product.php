<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\ProductFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin ProductFacadeInterface
 */
class Product extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ProductFacadeInterface::class;
    }
}
