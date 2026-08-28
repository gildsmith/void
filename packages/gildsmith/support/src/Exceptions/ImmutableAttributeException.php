<?php

declare(strict_types=1);

namespace Gildsmith\Support\Exceptions;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class ImmutableAttributeException extends LogicException
{
    public function __construct(Model $model, string $attribute)
    {
        $modelClass = $model::class;

        parent::__construct("Attribute [$attribute] is immutable on model [$modelClass].");
    }
}
