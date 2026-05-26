<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Facades;

use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Exceptions\MissingSoftDeletesException;
use Gildsmith\Support\Facades\Concerns\ValidatesSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use LogicException;

class UserFacade implements UserFacadeInterface
{
    use ValidatesSoftDeletes;

    /**
     * @return Collection<int, Model&UserInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function all(bool $withTrashed = false): Collection
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(UserInterface::class);

        return $withTrashed
            ? $this->ensureSoftDeletes($builder)->withTrashed()->get()
            : $builder->get();
    }

    /**
     * @throws ValidationException
     */
    public function create(array $data): Model&UserInterface
    {
        /** @var Builder $builder */
        $builder = resolve(UserInterface::class);

        return $builder->create($data);
    }

    /**
     * @throws LogicException
     */
    public function issueToken(Model&UserInterface $user): string
    {
        if (! method_exists($user, 'createToken')) {
            throw new LogicException('User model must support Sanctum tokens.');
        }

        return $user->createToken('api')->plainTextToken;
    }

    /**
     * @throws MissingSoftDeletesException
     * @throws ValidationException
     */
    public function login(string $email, string $password): (Model&UserInterface)|null
    {
        $user = $this->find($email);

        if ($user === null || ! Hash::check($password, (string) $user->getAttribute('password'))) {
            return null;
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return $user;
    }

    public function logout(Model&UserInterface $user, string $token): bool
    {
        $accessToken = PersonalAccessToken::findToken($token);

        if ($accessToken === null) {
            return false;
        }

        if ($accessToken->getAttribute('tokenable_type') !== $user->getMorphClass()) {
            return false;
        }

        if ((string) $accessToken->getAttribute('tokenable_id') !== (string) $user->getKey()) {
            return false;
        }

        return (bool) $accessToken->delete();
    }

    /**
     * @throws LogicException
     */
    public function logoutEverywhere(Model&UserInterface $user): bool
    {
        if (! method_exists($user, 'tokens')) {
            throw new LogicException('User model must support Sanctum tokens.');
        }

        return $user->tokens()->delete() > 0;
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function delete(string $code, bool $force = false): bool
    {
        $model = $this->find($code, $force);

        if ($model === null) {
            return false;
        }

        return $force
            ? $this->ensureSoftDeletes($model)->forceDelete()
            : $model->delete();
    }

    /**
     * Auth users use their email address as the CRUD facade code.
     *
     * @return (Model&UserInterface&SoftDeletes)|null
     *
     * @throws MissingSoftDeletesException
     */
    public function find(string $code, bool $withTrashed = false): (Model&UserInterface)|null
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(UserInterface::class);

        return $withTrashed
            ? $this->ensureSoftDeletes($builder)->withTrashed()->where('email', $code)->first()
            : $builder->where('email', $code)->first();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function restore(string $code): bool
    {
        $model = $this->find($code, true);

        if ($model === null) {
            return false;
        }

        return $this->ensureSoftDeletes($model)->restore();
    }

    /**
     * @return Collection<int, Model&UserInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function trashed(): Collection
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(UserInterface::class);

        return $this->ensureSoftDeletes($builder)->onlyTrashed()->get();
    }

    /**
     * @throws MissingSoftDeletesException
     * @throws ValidationException
     */
    public function update(string $code, array $data): (Model&UserInterface)|null
    {
        $model = $this->find($code, true);
        $model?->update($data);
        $model?->refresh();

        return $model;
    }

    /**
     * @throws ValidationException
     */
    public function updateOrCreate(string $code, array $data): Model&UserInterface
    {
        /** @var Builder $builder */
        $builder = resolve(UserInterface::class);

        return $builder->updateOrCreate(['email' => $code], $data);
    }
}
