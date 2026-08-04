<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Policies\Concerns;

use Gildsmith\Contract\User\UserInterface;

trait RequiresEmployeeAccess
{
    public function viewAny(mixed $user): bool
    {
        return $this->allows($user);
    }

    public function view(mixed $user): bool
    {
        return $this->allows($user);
    }

    public function create(mixed $user): bool
    {
        return $this->allows($user);
    }

    public function update(mixed $user): bool
    {
        return $this->allows($user);
    }

    public function delete(mixed $user): bool
    {
        return $this->allows($user);
    }

    public function viewTrashed(mixed $user): bool
    {
        return $this->allows($user);
    }

    public function trash(mixed $user): bool
    {
        return $this->allows($user);
    }

    public function restore(mixed $user): bool
    {
        return $this->allows($user);
    }

    protected function allows(mixed $user): bool
    {
        return $user instanceof UserInterface && $user->hasEmployeeAccess();
    }
}
