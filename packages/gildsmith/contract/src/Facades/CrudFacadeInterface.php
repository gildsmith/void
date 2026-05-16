<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Facades;

use Gildsmith\Contract\Models\HasCodeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Generic CRUD facade interface.
 *
 * @template TModel of HasCodeInterface
 */
interface CrudFacadeInterface
{
    /**
     * Retrieve a model by its unique code.
     */
    public function find(string $code): (Model&HasCodeInterface)|null;

    /**
     * Retrieve all models.
     *
     * @return Collection<int, TModel>
     */
    public function all(): Collection;

    /**
     * Create a new model using the provided data array.
     */
    public function create(array $data): Model&HasCodeInterface;

    /**
     * Update an existing model by its code.
     */
    public function update(string $code, array $data): Model&HasCodeInterface;

    /**
     * Create or update a model based on the given code.
     */
    public function updateOrCreate(string $code, array $data): Model&HasCodeInterface;

    /**
     * Delete a model by its code.
     */
    public function delete(string $code): bool;
}
