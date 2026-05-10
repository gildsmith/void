<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Product\ProductCollectionFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin ProductCollectionFacadeInterface
 */
class ProductCollection extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ProductCollectionFacadeInterface::class;
    }
}
