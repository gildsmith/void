<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Exceptions;

use RuntimeException;

class UserNotFoundException extends RuntimeException
{
    public function __construct(string $code)
    {
        parent::__construct("User [$code] does not identify a persisted auth user.");
    }
}
