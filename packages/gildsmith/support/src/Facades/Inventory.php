<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Inventory as InventoryFacade;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin InventoryFacade
 */
class Inventory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InventoryFacade::class;
    }
}
