<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\InventoryFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin InventoryFacadeInterface
 */
class Inventory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InventoryFacadeInterface::class;
    }
}
