<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Facades\Auth;

use Gildsmith\Contract\Facades\TrashableFacadeInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Exceptions\MissingSoftDeletesException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use LogicException;

/**
 * Facade for managing authenticated users.
 *
 * @extends TrashableFacadeInterface<UserInterface>
 */
interface UserFacadeInterface extends TrashableFacadeInterface
{
    /**
     * Register a storefront user and create the matching customer actor.
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

    /**
     * Issue a bearer token for the given user.
     *
     * @throws LogicException
     */
    public function issueToken(Model&UserInterface $user): string;

    /**
     * Revoke one bearer token for the given user.
     */
    public function logout(Model&UserInterface $user, string $token): bool;

    /**
     * Revoke every bearer token for the given user.
     *
     * @throws LogicException
     */
    public function logoutEverywhere(Model&UserInterface $user): bool;
}
