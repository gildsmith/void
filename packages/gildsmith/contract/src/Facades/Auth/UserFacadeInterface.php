<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Facades\Auth;

use Gildsmith\Contract\Facades\TrashableFacadeInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Exceptions\MissingSoftDeletesException;
use Illuminate\Database\Eloquent\Model;
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
    public function register(array $data): Model&UserInterface;

    /**
     * Attempt to authenticate a user by email and password.
     */
    public function login(string $email, string $password): (Model&UserInterface)|null;

    /**
     * Grant employee access to the given user.
     *
     * @throws MissingSoftDeletesException
     */
    public function grantEmployeeAccess(Model&UserInterface $user): Model&EmployeeInterface;

    /**
     * Revoke employee access from the given user.
     *
     * @throws MissingSoftDeletesException
     */
    public function revokeEmployeeAccess(Model&UserInterface $user): bool;
}
