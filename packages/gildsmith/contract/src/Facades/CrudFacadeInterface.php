<?php

namespace Gildsmith\Contract\Facades;

use Illuminate\Support\Collection;

/**
 * Generic CRUD facade interface.
 *
 * @template TModel
 */
interface CrudFacadeInterface
{
    /**
     * Retrieve a model by its unique code.
     *
     * @return TModel|null
     */
    public function find(string $code);

    /**
     * Retrieve all models.
     *
     * @return Collection<int, TModel>
     */
    public function all(): Collection;

    /**
     * Create a new model using the provided data array.
     *
     * @return TModel
     */
    public function create(array $data);

    /**
     * Update an existing model by its code.
     *
     * @return TModel
     */
    public function update(string $code, array $data);

    /**
     * Create or update a model based on the given code.
     *
     * @return TModel
     */
    public function updateOrCreate(string $code, array $data);

    /**
     * Delete a model by its code.
     */
    public function delete(string $code): bool;
}
