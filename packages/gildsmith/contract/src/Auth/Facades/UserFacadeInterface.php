<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Auth\Facades;

use Gildsmith\Contract\Shared\Facades\TrashableFacadeInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Validation\ValidationException;

/**
 * Facade for managing authenticated users.
 *
 * @extends TrashableFacadeInterface<UserInterface>
 */
interface UserFacadeInterface extends TrashableFacadeInterface
{
    /**
     * Register a storefront user and create the matching customer profile.
     *
     * @throws ValidationException
     */
    public function register(array $data): UserInterface;

    /**
     * Attempt to authenticate a user by email and password.
     */
    public function login(string $email, string $password): ?UserInterface;

    /**
     * Grant employee access to the given user.
     */
    public function grantEmployeeAccess(UserInterface $user): EmployeeInterface;

    /**
     * Revoke employee access from the given user.
     */
    public function revokeEmployeeAccess(UserInterface $user): bool;
}
