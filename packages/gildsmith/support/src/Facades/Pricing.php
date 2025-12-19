<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\PricingFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin PricingFacadeInterface
 */
class Pricing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PricingFacadeInterface::class;
    }
}
