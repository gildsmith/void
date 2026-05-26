<?php

declare(strict_types=1);

namespace Gildsmith\Support\Requests\Concerns;

use Gildsmith\Support\Model\Contracts\HasValidationRulesInterface;
use Illuminate\Database\Eloquent\Model;
use LogicException;

trait ValidatesResourceRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws LogicException
     */
    protected function resolveValidationModel(): Model&HasValidationRulesInterface
    {
        $model = $this->validationModel ?? null;

        if ($model === null) {
            throw new LogicException(sprintf(
                'The request [%s] must define a validation model interface.',
                static::class,
            ));
        }

        $model = resolve($model);

        if (! $model instanceof Model) {
            throw new LogicException(sprintf(
                'The validation model [%s] must extend [%s].',
                get_debug_type($model),
                Model::class,
            ));
        }

        if (! $model instanceof HasValidationRulesInterface) {
            throw new LogicException(sprintf(
                'The validation model [%s] must implement [%s].',
                get_debug_type($model),
                HasValidationRulesInterface::class,
            ));
        }

        return $model;
    }
}
