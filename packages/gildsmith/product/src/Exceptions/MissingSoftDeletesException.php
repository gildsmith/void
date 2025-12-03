<?php

declare(strict_types=1);

namespace Gildsmith\Product\Exceptions;

use Exception;

class MissingSoftDeletesException extends Exception
{
    public function __construct(object $model)
    {
        $class = get_class($model);
        parent::__construct("The model [$class] must use the SoftDeletes trait.");
    }
}
