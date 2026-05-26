<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Facades\Auth;

use Gildsmith\Contract\Facades\TrashableFacadeInterface;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Database\Eloquent\Model;
use LogicException;

/**
 * Facade for managing authenticated users.
 *
 * @extends TrashableFacadeInterface<UserInterface>
 */
interface UserFacadeInterface extends TrashableFacadeInterface
{
    /**
     * Attempt to authenticate a user by email and password.
     */
    public function login(string $email, string $password): (Model&UserInterface)|null;

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
