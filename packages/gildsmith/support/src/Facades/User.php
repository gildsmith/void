<?php

declare(strict_types=1);

namespace Gildsmith\Support\Facades;

use Gildsmith\Contract\Auth\Facades\UserFacadeInterface;
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
