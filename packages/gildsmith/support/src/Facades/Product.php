<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Product as ProductFacade;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin ProductFacade
 */
class Product extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ProductFacade::class;
    }
}
