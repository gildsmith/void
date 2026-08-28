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
            $requestClass = static::class;

            throw new LogicException(
                "The request [$requestClass] must define a validation model interface.",
            );
        }

        $model = resolve($model);

        if (! $model instanceof Model) {
            $modelType = get_debug_type($model);
            $modelClass = Model::class;

            throw new LogicException(
                "The validation model [$modelType] must extend [$modelClass].",
            );
        }

        if (! $model instanceof HasValidationRulesInterface) {
            $modelType = get_debug_type($model);
            $interfaceClass = HasValidationRulesInterface::class;

            throw new LogicException(
                "The validation model [$modelType] must implement [$interfaceClass].",
            );
        }

        return $model;
    }
}
