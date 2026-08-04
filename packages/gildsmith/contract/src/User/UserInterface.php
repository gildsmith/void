<?php

declare(strict_types=1);

namespace Gildsmith\Contract\User;

use Gildsmith\Contract\Models\HasCodeInterface;

/**
 * This interface represents registered and authenticated user.
 */
interface UserInterface extends HasCodeInterface
{
    /**
     * Determine whether this user currently has employee access.
     */
    public function hasEmployeeAccess(): bool;
}
