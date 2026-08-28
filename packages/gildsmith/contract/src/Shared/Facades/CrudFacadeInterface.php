<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Shared\Facades;

use Gildsmith\Contract\Shared\HasCodeInterface;
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
     *
     * @return TModel|null
     */
    public function find(string $code): ?HasCodeInterface;

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
    public function create(array $data): HasCodeInterface;

    /**
     * Update an existing model by its code.
     *
     * @return TModel|null
     */
    public function update(string $code, array $data): ?HasCodeInterface;

    /**
     * Create or update a model based on the given code.
     *
     * @return TModel
     */
    public function updateOrCreate(string $code, array $data): HasCodeInterface;

    /**
     * Delete a model by its code.
     */
    public function delete(string $code): bool;
}
