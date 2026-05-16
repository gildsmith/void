<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Facades;

use Gildsmith\Contract\Models\HasCodeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * CRUD facade with soft-delete support.
 *
 * @template TModel of HasCodeInterface
 *
 * @extends CrudFacadeInterface<TModel>
 */
interface TrashableFacadeInterface extends CrudFacadeInterface
{
    /**
     * Retrieve a model by code, including soft-deleted models.
     */
    public function find(string $code, bool $withTrashed = false): (Model&HasCodeInterface)|null;

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
