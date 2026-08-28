<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Auth;

interface SessionInterface
{
    public function isActive(): bool;

    public function revoke(): bool;
}
