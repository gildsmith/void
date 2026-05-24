<?php

declare(strict_types=1);

namespace Gildsmith\Support\Requests\Concerns;

use Gildsmith\Support\Model\Concerns\HasValidationRules;
use LogicException;

trait ValidatesResourceRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function resolveValidationModel(): object
    {
        $model = $this->validationModel ?? null;

        if ($model === null) {
            throw new LogicException(sprintf(
                'The request [%s] must define a validation model interface.',
                static::class,
            ));
        }

        $model = resolve($model);

        if (! in_array(HasValidationRules::class, class_uses_recursive($model), true)) {
            throw new LogicException(sprintf(
                'The model [%s] must use the [%s] trait.',
                get_debug_type($model),
                HasValidationRules::class,
            ));
        }

        return $model;
    }
}
