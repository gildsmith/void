<?php

declare(strict_types=1);

namespace Gildsmith\Product\Facades;

use Gildsmith\Contract\Facades\Product\ProductFacadeInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Gildsmith\Support\Traits\ValidatesSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class ProductFacade implements ProductFacadeInterface
{
    use ValidatesSoftDeletes;

    /**
     * @return Collection<int, Model&ProductInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function all(bool $withTrashed = false): Collection
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(ProductInterface::class);

        return $withTrashed
            ? $this->ensureSoftDeletes($builder)->withTrashed()->get()
            : $builder->get();
    }

    public function create(array $data): ProductInterface
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

        return $builder->create($data);
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function delete(string $code, bool $force = false): bool
    {
        $model = $this->find($code, $force);

        if ($model == null) {
            return false;
        }

        return $force
            ? $this->ensureSoftDeletes($model)->forceDelete()
            : $model->delete();
    }

    /**
     * @return (Model&ProductInterface&SoftDeletes)|null
     *
     * @throws MissingSoftDeletesException
     */
    public function find(string $code, bool $withTrashed = false): ?ProductInterface
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(ProductInterface::class);

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

        if ($model === null) {
            return false;
        }

        return $this->ensureSoftDeletes($model)->restore();
    }

    /**
     * @return Collection<int, Model&ProductInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function trashed(): Collection
    {
        /** @var Builder&SoftDeletes $builder */
        $builder = resolve(ProductInterface::class);

        return $this->ensureSoftDeletes($builder)->onlyTrashed()->get();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function update(string $code, array $data): ProductInterface
    {
        $model = $this->find($code, true);

        if ($model === null) {
            throw new \InvalidArgumentException("Product [{$code}] does not exist.");
        }

        $model->update($data);

        return $model->fresh();
    }

    public function updateOrCreate(string $code, array $data): ProductInterface
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

        return $builder->updateOrCreate(['code' => $code], $data);
    }
}
