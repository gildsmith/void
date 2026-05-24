<?php

declare(strict_types=1);

namespace Gildsmith\Support\Utils;

abstract class ValidationRules
{
    const string CODE = 'string|regex:/^[a-z0-9._-]+$/';
}
