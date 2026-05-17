# Facades and Contracts

Gildsmith uses facades to keep package workflows contained and uniform. Models
describe persistence, but package-level creation, updates, deletes, restores, and
lookups should go through a facade whenever the operation is part of the public
package API.

## Why facades

Facades give each package one predictable place to expose its application
workflow. That makes it easier to:

- Keep controllers small.
- Swap concrete implementations behind contracts.
- Test behavior through a stable public API.
- Avoid scattering create and update rules throughout the codebase.

## CRUD facade contract

The base contract is `Gildsmith\Contract\Facades\CrudFacadeInterface`.

It requires:

```php
public function find(string $code);

public function all(): Collection;

public function create(array $data);

public function update(string $code, array $data);

public function updateOrCreate(string $code, array $data);

public function delete(string $code): bool;
```

The important convention is that records are addressed by `code`, not by
database id. Database ids are storage details. Codes are the package API.

Models used with CRUD facades should implement
`Gildsmith\Contract\Models\HasCodeInterface`. Eloquent models can use the support
concern:

```php
use Gildsmith\Support\Model\Concerns\HasCode;

class Product extends Model implements ProductInterface
{
    use HasCode;
}
```

The base facade contract is typed around that capability:

```php
public function find(string $code): (Model&HasCodeInterface)|null;

public function create(array $data): Model&HasCodeInterface;

public function update(string $code, array $data): (Model&HasCodeInterface)|null;

public function updateOrCreate(string $code, array $data): Model&HasCodeInterface;
```

Domain-specific facade implementations should narrow those returns to their own
interfaces, such as `Model&ProductInterface`.

## Trashable facade contract

Soft-deletable resources should implement
`Gildsmith\Contract\Facades\TrashableFacadeInterface`.

It extends CRUD and adds:

```php
public function find(string $code, bool $withTrashed = false);

public function all(bool $withTrashed = false): Collection;

public function trashed(): Collection;

public function restore(string $code): bool;

public function delete(string $code, bool $force = false): bool;
```

Use this contract when the model uses Laravel's `SoftDeletes` trait.

## Implementing a facade

A concrete facade implementation should live in the domain package:

```php
namespace Gildsmith\Product\Facades;

use Gildsmith\Contract\Facades\Product\ProductFacadeInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductFacade implements ProductFacadeInterface
{
    public function all(bool $withTrashed = false): Collection
    {
        /** @var Builder $builder */
        $builder = resolve(ProductInterface::class);

        return $builder->get();
    }
}
```

The package service provider binds the contract to the concrete:

```php
use Gildsmith\Contract\Facades\Product\ProductFacadeInterface;
use Gildsmith\Product\Facades\ProductFacade;

$this->app->bind(ProductFacadeInterface::class, fn () => new ProductFacade);
```

The support package exposes the Laravel facade class:

```php
use Gildsmith\Support\Facades\Product;

$product = Product::find('linen-shirt');
```

## Testing a facade

Use `gildsmith/testing` to verify the contract from the package test suite:

```php
use Gildsmith\Product\Facades\ProductFacade as ProductFacadeConcrete;
use Gildsmith\Product\Models\Product;
use Gildsmith\Support\Facades\Product as ProductFacade;

covers(ProductFacadeConcrete::class);

itFulfillsTrashableFacadeContract(
    facade: ProductFacade::class,
    model: Product::class,
);
```

The helper expects the model factory to create a complete valid record. If a
model requires relations or translated fields, put that knowledge in the
factory. The test helper should only receive overrides for unusual scenarios.

## Creating your own facade contract

Create a new facade contract when a package exposes a public workflow that other
packages may call.

```php
namespace Gildsmith\Contract\Facades\Inventory;

use Gildsmith\Contract\Facades\TrashableFacadeInterface;
use Gildsmith\Contract\Inventory\WarehouseInterface;

/**
 * @extends TrashableFacadeInterface<WarehouseInterface>
 */
interface WarehouseFacadeInterface extends TrashableFacadeInterface
{
    //
}
```

Then implement it in the domain package and bind it in the package service
provider.

## Keep model rules close to models

Do not put model invariants in a single facade method when the rule should apply
to every write path. For example, immutable model attributes should be declared
on the model and enforced by support-level model concerns.

```php
use Gildsmith\Support\Model\Concerns\HasImmutableAttributes;

class Product extends Model
{
    use HasImmutableAttributes;

    protected array $immutable = ['code'];
}
```

The facade still owns the workflow, but the model owns invariants that must hold
no matter how the model is saved.
