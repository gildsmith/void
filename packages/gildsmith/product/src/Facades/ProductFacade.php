<?php

declare(strict_types=1);

namespace Gildsmith\Product\Facades;

use Gildsmith\Contract\Facades\Product\AttributeFacadeInterface;
use Gildsmith\Contract\Facades\Product\AttributeValueFacadeInterface;
use Gildsmith\Contract\Facades\Product\BlueprintFacadeInterface;
use Gildsmith\Contract\Facades\Product\ProductCollectionFacadeInterface;
use Gildsmith\Contract\Facades\ProductFacadeInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class ProductFacade implements ProductFacadeInterface
{
    /**
     * @return Collection<int, Model&ProductInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function all(bool $withTrashed = false): Collection
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

        $withTrashed && $this->ensureSoftDeletes($builder);

        return $withTrashed
            ? $builder::withTrashed()->get()
            : $builder->get();
    }

    public function create(array $data): ProductInterface
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

        return $builder::create($data);
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function delete(string $code, bool $force = false): bool
    {
        $product = $this->find($code);

        $force && $this->ensureSoftDeletes($product);

        return $force
            ? (bool) $product->forceDelete()
            : (bool) $product->delete();
    }

    /**
     * @return (Model&ProductInterface)|null
     *
     * @throws MissingSoftDeletesException
     */
    public function find(string $code, bool $withTrashed = false): ?ProductInterface
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

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
     * @return Collection<int, Model&ProductInterface>
     *
     * @throws MissingSoftDeletesException
     */
    public function trashed(): Collection
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

        $this->ensureSoftDeletes($builder);

        return $builder::onlyTrashed()->get();
    }

    /**
     * @throws MissingSoftDeletesException
     */
    public function update(string $code, array $data): ProductInterface
    {
        $product = $this->find($code, true);

        $product->update($data);

        return $product->fresh();
    }

    public function updateOrCreate(string $code, array $data): ProductInterface
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

        return $builder::updateOrCreate(['code' => $code], $data);
    }

    public function attribute(): AttributeFacadeInterface
    {
        return resolve(AttributeFacadeInterface::class);
    }

    public function attributeValue(): AttributeValueFacadeInterface
    {
        return resolve(AttributeValueFacadeInterface::class);
    }

    public function blueprint(): BlueprintFacadeInterface
    {
        return resolve(BlueprintFacadeInterface::class);
    }

    public function collection(): ProductCollectionFacadeInterface
    {
        return resolve(ProductCollectionFacadeInterface::class);
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
