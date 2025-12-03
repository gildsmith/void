<?php

namespace Gildsmith\Contract\Facades;

use Gildsmith\Contract\Product\ProductInterface;
use Illuminate\Support\Collection;

/**
 * CRUD facade with soft-delete support.
 *
 * @template TModel
 * @extends CrudFacadeInterface<TModel>
 */
interface TrashableFacadeInterface extends CrudFacadeInterface
{
    /**
     * Retrieve a model by code, including soft-deleted models.
     *
     * @return TModel|null
     */
    public function find(string $code, bool $withTrashed = false);

    /**
     * Retrieve all models.
     *
     * @return Collection<int, TModel>
     */
    public function all(bool $withTrashed = false): Collection;

    /**
     * Retrieve only soft-deleted models.
     *
     * @return Collection<int, TModel>
     */
    public function trashed(): Collection;

    /**
     * Restore a soft-deleted model by its code.
     */
    public function restore(string $code): bool;

    /**
     * Permanently delete a model by its code.
     */
    public function delete(string $code, bool $force = false): bool;
}
