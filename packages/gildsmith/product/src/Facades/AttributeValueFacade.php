<?php

declare(strict_types=1);

namespace Gildsmith\Product\Facades;

use Gildsmith\Contract\Facades\Product\AttributeValueFacadeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class AttributeValueFacade implements AttributeValueFacadeInterface
{
    /**
     * @return Collection<int, Model&AttributeValueInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function all(bool $withTrashed = false): Collection
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeValueInterface::class);

        $withTrashed && $this->ensureSoftDeletes($builder);

        return $withTrashed
            ? $builder::withTrashed()->get()
            : $builder->get();
    }

    public function create(array $data): AttributeValueInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeValueInterface::class);

        return $builder::create($data);
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function delete(string $code, bool $force = false): bool
    {
        $attributeValue = $this->find($code);

        $force && $this->ensureSoftDeletes($attributeValue);

        return $force
            ? (bool) $attributeValue->forceDelete()
            : (bool) $attributeValue->delete();
    }

    /**
     * @return (Model&AttributeValueInterface)|null
     *
     * @throws MissingSoftDeletesException
     */
    public function find(string $code, bool $withTrashed = false): ?AttributeValueInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeValueInterface::class);

        $withTrashed && $this->ensureSoftDeletes($builder);

        return $withTrashed
            ? $builder::withTrashed()->where('code', $code)->first()
            : $builder::where('code', $code)->first();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function restore(string $code): bool
    {
        /** @var SoftDeletes $model */
        $model = $this->find($code, true);

        $this->ensureSoftDeletes($model);

        return $model->restore();
    }

    /**
     * @return Collection<int, Model&AttributeValueInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function trashed(): Collection
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeValueInterface::class);

        $this->ensureSoftDeletes($builder);

        return $builder::onlyTrashed()->get();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function update(string $code, array $data): AttributeValueInterface
    {
        $attributeValue = $this->find($code, true);

        $attributeValue->update($data);

        return $attributeValue->fresh();
    }

    public function updateOrCreate(string $code, array $data): AttributeValueInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeValueInterface::class);

        return $builder::updateOrCreate(['code' => $code], $data);
    }

    /**
     * A simple method allowing you to check
     * whether a class implements SoftDeletes.
     *
     * @see SoftDeletes
     */
    public function usesSoftDeletes(object|string $model): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($model));
    }

    /**
     * This method makes sure that SoftDeletes
     * is used by a registered model, as many methods
     * in the Facade operate on methods it provides.
     *
     * @throws MissingSoftDeletesException
     */
    protected function ensureSoftDeletes(object $model): void
    {
        if (! $this->usesSoftDeletes($model)) {
            throw new MissingSoftDeletesException($model);
        }
    }
}
