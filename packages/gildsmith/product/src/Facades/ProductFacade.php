<?php

declare(strict_types=1);

namespace Gildsmith\Product\Facades;

use Gildsmith\Contract\Product\Facades\ProductFacadeInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Models\Product;
use Gildsmith\Support\Exceptions\ImmutableAttributeException;
use Illuminate\Support\Collection;

class ProductFacade implements ProductFacadeInterface
{
    /**
     * Return products, optionally including soft-deleted records.
     *
     * @return Collection<int, ProductInterface>
     */
    public function all(bool $withTrashed = false): Collection
    {
        $query = Product::query();

        return $withTrashed
            ? $query->withTrashed()->get()
            : $query->get();
    }

    /**
     * Create a product from caller-supplied data.
     */
    public function create(array $data): ProductInterface
    {
        return Product::query()->create($data);
    }

    /**
     * Return false when no product has the requested code.
     */
    public function delete(string $code, bool $force = false): bool
    {
        $product = $this->findModel($code, $force);

        if ($product === null) {
            return false;
        }

        return $force
            ? $product->forceDelete()
            : $product->delete();
    }

    /**
     * Return null when no product has the requested code.
     */
    public function find(string $code, bool $withTrashed = false): ?ProductInterface
    {
        return $this->findModel($code, $withTrashed);
    }

    /**
     * Return false when no product has the requested code.
     */
    public function restore(string $code): bool
    {
        $product = $this->findModel($code, true);

        if ($product === null) {
            return false;
        }

        return $product->restore();
    }

    /**
     * Return only soft-deleted products.
     *
     * @return Collection<int, ProductInterface>
     */
    public function trashed(): Collection
    {
        return Product::query()->onlyTrashed()->get();
    }

    /**
     * Return null when no product has the requested code.
     */
    public function update(string $code, array $data): ?ProductInterface
    {
        $product = $this->findModel($code, true);

        if ($product === null) {
            return null;
        }

        $product->update($data);

        return $product->refresh();
    }

    /**
     * Update the identified product or create it when it does not exist.
     *
     * @throws ImmutableAttributeException when input attempts to replace the stable code
     */
    public function updateOrCreate(string $code, array $data): ProductInterface
    {
        if (array_key_exists('code', $data) && $data['code'] !== $code) {
            throw new ImmutableAttributeException(new Product, 'code');
        }

        unset($data['code']);

        return Product::query()->updateOrCreate(['code' => $code], $data);
    }

    private function findModel(string $code, bool $withTrashed = false): ?Product
    {
        $query = Product::query();

        return $withTrashed
            ? $query->withTrashed()->where('code', $code)->first()
            : $query->where('code', $code)->first();
    }
}
