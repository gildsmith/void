<?php

declare(strict_types=1);

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Product\Facades\ProductFacadeInterface;
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
