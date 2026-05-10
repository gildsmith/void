<?php

namespace Gildsmith\Support\Traits;

use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait ValidatesSoftDeletes
{
    /**
     * A simple method allowing you to check
     * whether a class implements SoftDeletes.
     *
     * @see SoftDeletes
     */
    public function usesSoftDeletes(object|string $model): bool
    {
        if ($model instanceof Builder) {
            $model = $model->getModel();
        }

        return in_array(SoftDeletes::class, class_uses_recursive($model));
    }

    /**
     * This method makes sure that SoftDeletes
     * is used by a registered model, as many methods
     * in the Facade operate on methods it provides.
     *
     * @template TModel of Model|Builder
     *
     * @phpstan-param Model $model
     *
     * @phpstan-return Model&SoftDeletes
     *
     * @throws MissingSoftDeletesException
     */
    protected function ensureSoftDeletes(Model|Builder $model): Model|Builder
    {
        return ! $this->usesSoftDeletes($model)
            ? throw new MissingSoftDeletesException($model)
            : $model;
    }
}
