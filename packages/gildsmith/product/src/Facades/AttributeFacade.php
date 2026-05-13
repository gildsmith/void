<?php

declare(strict_types=1);

namespace Gildsmith\Product\Facades;

use Gildsmith\Contract\Facades\Product\AttributeFacadeInterface;
use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Gildsmith\Support\Traits\ValidatesSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class AttributeFacade implements AttributeFacadeInterface
{
    use ValidatesSoftDeletes;

    /**
     * @return Collection<int, Model&AttributeInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function all(bool $withTrashed = false): Collection
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(AttributeInterface::class);

        return $withTrashed
            ? $this->ensureSoftDeletes($builder)->withTrashed()->get()
            : $builder->get();
    }

    public function create(array $data): AttributeInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeInterface::class);

        return $builder->create($data);
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function delete(string $code, bool $force = false): bool
    {
        $model = $this->find($code);

        return $force
            ? $this->ensureSoftDeletes($model)->forceDelete()
            : $model->delete();
    }

    /**
     * @return (Model&AttributeInterface&SoftDeletes)|null
     *
     * @throws MissingSoftDeletesException
     */
    public function find(string $code, bool $withTrashed = false): ?AttributeInterface
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(AttributeInterface::class);

        return $withTrashed
            ? $this->ensureSoftDeletes($builder)->withTrashed()->where('code', $code)->first()
            : $builder->where('code', $code)->first();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function restore(string $code): bool
    {
        $model = $this->find($code, true);

        return $this->ensureSoftDeletes($model)->restore();
    }

    /**
     * @return Collection<int, Model&AttributeInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function trashed(): Collection
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(AttributeInterface::class);

        return $this->ensureSoftDeletes($builder)->onlyTrashed()->get();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function update(string $code, array $data): AttributeInterface
    {
        $model = $this->find($code, true);
        $model->update($data);

        return $model->fresh();
    }

    public function updateOrCreate(string $code, array $data): AttributeInterface
    {
        /** @var Builder $builder */
        $builder = resolve(AttributeInterface::class);

        return $builder->updateOrCreate(['code' => $code], $data);
    }
}
