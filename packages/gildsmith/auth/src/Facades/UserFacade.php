<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Facades;

use Gildsmith\Auth\Exceptions\UserNotFoundException;
use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Gildsmith\Contract\Auth\Facades\UserFacadeInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Exceptions\ImmutableAttributeException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserFacade implements UserFacadeInterface
{
    /**
     * @return Collection<int, UserInterface>
     */
    public function all(bool $withTrashed = false): Collection
    {
        $query = User::query();

        return $withTrashed
            ? $query->withTrashed()->get()
            : $query->get();
    }

    /**
     * @throws ValidationException
     */
    public function create(array $data): UserInterface
    {
        return User::query()->create($data);
    }

    public function delete(string $code, bool $force = false): bool
    {
        $user = $this->findModel($code, $force);

        if ($user === null) {
            return false;
        }

        return $force
            ? (bool) $user->forceDelete()
            : (bool) $user->delete();
    }

    /**
     * Auth users use their email address as their stable public code.
     */
    public function find(string $code, bool $withTrashed = false): ?UserInterface
    {
        return $this->findModel($code, $withTrashed);
    }

    /**
     * Restore existing employee access instead of creating duplicate employee records.
     *
     * @throws UserNotFoundException
     */
    public function grantEmployeeAccess(UserInterface $user): EmployeeInterface
    {
        $user = $this->resolveUserModel($user);

        $employee = Employee::query()
            ->withTrashed()
            ->where('user_id', $user->getKey())
            ->first();

        if ($employee === null) {
            return Employee::query()->create([
                'user_id' => $user->getKey(),
            ]);
        }

        if ($employee->trashed()) {
            $employee->restore();
        }

        return $employee->refresh();
    }

    /**
     * Attempt to authenticate without exposing password storage or login bookkeeping.
     *
     * @throws ValidationException
     */
    public function login(string $email, string $password): ?UserInterface
    {
        $user = $this->findModel($email);

        if ($user === null || !Hash::check($password, (string) $user->getAttribute('password'))) {
            return null;
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return $user->refresh();
    }

    /**
     * @throws ValidationException
     */
    public function register(array $data): UserInterface
    {
        return DB::transaction(function () use ($data): UserInterface {
            $user = User::query()->create($data);

            Customer::query()->create([
                'user_id' => $user->getKey(),
            ]);

            return $user->load('customer', 'employee');
        });
    }

    public function restore(string $code): bool
    {
        $user = $this->findModel($code, true);

        if ($user === null) {
            return false;
        }

        return (bool) $user->restore();
    }

    public function revokeEmployeeAccess(UserInterface $user): bool
    {
        $user = $this->findUserModel($user);

        if ($user === null) {
            return false;
        }

        $employee = Employee::query()
            ->where('user_id', $user->getKey())
            ->first();

        if ($employee === null) {
            return false;
        }

        return (bool) $employee->delete();
    }

    /**
     * @return Collection<int, UserInterface>
     */
    public function trashed(): Collection
    {
        return User::query()->onlyTrashed()->get();
    }

    /**
     * @throws ValidationException
     */
    public function update(string $code, array $data): ?UserInterface
    {
        $user = $this->findModel($code, true);

        if ($user === null) {
            return null;
        }

        $user->update($data);

        return $user->refresh();
    }

    /**
     * Use the method's code as the stable identity and reject conflicting email data.
     *
     * @throws ImmutableAttributeException
     * @throws ValidationException
     */
    public function updateOrCreate(string $code, array $data): UserInterface
    {
        if (array_key_exists('email', $data) && $data['email'] !== $code) {
            throw new ImmutableAttributeException(new User(), 'email');
        }

        unset($data['email']);

        return User::query()->updateOrCreate(['email' => $code], $data);
    }

    private function findModel(string $code, bool $withTrashed = false): ?User
    {
        $query = User::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->where('email', $code)->first();
    }

    private function findUserModel(UserInterface $user): ?User
    {
        if ($user instanceof User && $user->exists) {
            return $user;
        }

        return $this->findModel($user->code, true);
    }

    /**
     * @throws UserNotFoundException
     */
    private function resolveUserModel(UserInterface $user): User
    {
        return $this->findUserModel($user)
            ?? throw new UserNotFoundException($user->code);
    }
}
