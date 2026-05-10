<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Product\AttributeValueFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin AttributeValueFacadeInterface
 */
class AttributeValue extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AttributeValueFacadeInterface::class;
    }
}
