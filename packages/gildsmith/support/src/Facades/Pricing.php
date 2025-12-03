<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Pricing as PricingFacade;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin PricingFacade
 */
class Pricing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PricingFacade::class;
    }
}
