<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Facades;

use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Contract\User\CustomerInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Exceptions\MissingSoftDeletesException;
use Gildsmith\Support\Facades\Concerns\ValidatesSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
     * @throws ValidationException
     */
    public function register(array $data): Model&UserInterface
    {
        return DB::transaction(function () use ($data): Model&UserInterface {
            $user = $this->create($data);

            /** @var Builder $builder */
            $builder = resolve(CustomerInterface::class);
            $builder->create([
                'user_id' => $user->getKey(),
            ]);

            return $user->load('customer', 'employee');
        });
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

        return $user->refresh();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function grantEmployeeAccess(Model&UserInterface $user): Model&EmployeeInterface
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(EmployeeInterface::class);

        /** @var (Model&EmployeeInterface&SoftDeletes)|null $employee */
        $employee = $this->ensureSoftDeletes($builder)
            ->withTrashed()
            ->where('user_id', $user->getKey())
            ->first();

        if ($employee === null) {
            return $builder->create([
                'user_id' => $user->getKey(),
            ]);
        }

        $this->ensureSoftDeletes($employee)->restore();
        $employee->refresh();

        return $employee;
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function revokeEmployeeAccess(Model&UserInterface $user): bool
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(EmployeeInterface::class);

        /** @var (Model&EmployeeInterface)|null $employee */
        $employee = $builder
            ->where('user_id', $user->getKey())
            ->first();

        if ($employee === null) {
            return false;
        }

        return $this->ensureSoftDeletes($employee)->delete();
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
