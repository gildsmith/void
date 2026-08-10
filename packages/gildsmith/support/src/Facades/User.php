<?php

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin UserFacadeInterface
 */
class User extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UserFacadeInterface::class;
    }
}
