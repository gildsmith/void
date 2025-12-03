<?php

declare(strict_types=1);

namespace Gildsmith\Product\Facades;

use Gildsmith\Contract\Facades\Product\AttributeFacadeInterface;
use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class AttributeFacade implements AttributeFacadeInterface
{
    /**
     * @return Collection<int, Model&AttributeInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function all(bool $withTrashed = false): Collection
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeInterface::class);

        $withTrashed && $this->ensureSoftDeletes($builder);

        return $withTrashed
            ? $builder::withTrashed()->get()
            : $builder->get();
    }

    public function create(array $data): AttributeInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeInterface::class);

        return $builder::create($data);
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function delete(string $code, bool $force = false): bool
    {
        $attribute = $this->find($code);

        $force && $this->ensureSoftDeletes($attribute);

        return $force
            ? (bool) $attribute->forceDelete()
            : (bool) $attribute->delete();
    }

    /**
     * @return (Model&AttributeInterface)|null
     *
     * @throws MissingSoftDeletesException
     */
    public function find(string $code, bool $withTrashed = false): ?AttributeInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeInterface::class);

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
     * @return Collection<int, Model&AttributeInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function trashed(): Collection
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeInterface::class);

        $this->ensureSoftDeletes($builder);

        return $builder::onlyTrashed()->get();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function update(string $code, array $data): AttributeInterface
    {
        $attribute = $this->find($code, true);

        $attribute->update($data);

        return $attribute->fresh();
    }

    public function updateOrCreate(string $code, array $data): AttributeInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeInterface::class);

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
