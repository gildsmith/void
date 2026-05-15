<?php

declare(strict_types=1);

namespace Gildsmith\Support\Exceptions;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class ImmutableAttributeException extends LogicException
{
    public function __construct(Model $model, string $attribute)
    {
        parent::__construct(sprintf(
            'Attribute [%s] is immutable on model [%s].',
            $attribute,
            $model::class,
        ));
    }
}
