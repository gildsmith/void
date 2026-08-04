<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Policies\Product;

use Gildsmith\Auth\Policies\Concerns\RequiresEmployeeAccess;

class BlueprintPolicy
{
    use RequiresEmployeeAccess;
}
