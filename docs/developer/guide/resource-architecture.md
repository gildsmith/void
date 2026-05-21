# Resource Architecture

Gildsmith is package-oriented, but most packages still describe the same kind of
thing: resources that can be created, found, updated, deleted, authorized, and
tested in a predictable way.

You do not have to follow every convention on this page. A package can always
drop down to plain Laravel when its domain needs something else. For first-party
style Gildsmith packages, though, this is the expected shape. Keeping resources
uniform makes packages easier to read, test, replace, and connect to each other.

## Resources have codes

Public resources should have a `code`.

A code is a human-readable identifier used by package APIs, routes, facades, and
tests to find a record. It is not the same as the database id. The id is a
storage detail; the code is the stable identifier other parts of the system can
talk about.

For example, a product may have the code `linen-shirt`, and an attribute may have
the code `color`.

Resource tables should make codes unique and indexed:

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->string('name');
    $table->softDeletes();
    $table->timestamps();
});
```

Use an explicit unique index if the column definition needs to be more complex:

```php
$table->string('code');
$table->unique('code');
```

## Models expose code consistently

Models that are addressed by code should implement
`Gildsmith\Contract\Models\HasCodeInterface`.

Eloquent models can use the support concern:

```php
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Support\Model\Concerns\HasCode;
use Gildsmith\Support\Model\Concerns\HasImmutableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements ProductInterface
{
    use HasCode;
    use HasImmutableAttributes;
    use SoftDeletes;

    protected array $immutable = ['code'];

    protected $fillable = [
        'code',
        'name',
    ];
}
```

Treat `code` as immutable for normal resource models. Renaming the public
identifier of a record after other packages or clients may have stored it is a
very different operation from updating a name or description.

## Workflows go through facades

Package-level writes should go through facades. Controllers, commands, and other
packages should ask the facade to create, update, delete, trash, or restore a
resource instead of scattering those rules around the codebase.

The base contracts are:

- `Gildsmith\Contract\Facades\CrudFacadeInterface`
- `Gildsmith\Contract\Facades\TrashableFacadeInterface`

CRUD resources support:

```php
public function find(string $code);
public function all(): Collection;
public function create(array $data);
public function update(string $code, array $data);
public function updateOrCreate(string $code, array $data);
public function delete(string $code): bool;
```

Trashable resources add soft-delete aware reads and restore behavior:

```php
public function find(string $code, bool $withTrashed = false);
public function all(bool $withTrashed = false): Collection;
public function trashed(): Collection;
public function restore(string $code): bool;
public function delete(string $code, bool $force = false): bool;
```

Domain packages should expose their own facade contracts by extending the shared
contracts:

```php
use Gildsmith\Contract\Facades\TrashableFacadeInterface;
use Gildsmith\Contract\Product\ProductInterface;

/**
 * @extends TrashableFacadeInterface<ProductInterface>
 */
interface ProductFacadeInterface extends TrashableFacadeInterface
{
    //
}
```

Concrete facades may return more specific model contracts, but they should keep
the CRUD behavior compatible with the shared contract. For example, `update`
returns `null` when the resource is not found.

## Routes use abilities

Gildsmith packages should not hardcode authentication. Authentication belongs to
the Laravel application using the package.

Packages should protect resource routes with authorization abilities and let the
application decide who can perform them through policies or gates. Use
`Gildsmith\Contract\Routing\ResourceAbility` instead of spelling ability names by
hand:

```php
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Contract\Routing\ResourceAbility as Ability;
use Illuminate\Support\Facades\Route;

Route::post('/products', ProductCreateController::class)
    ->can(Ability::Create->value, ProductInterface::class);

Route::patch('/products/{code}', ProductUpdateController::class)
    ->can(Ability::Update->value, ProductInterface::class);

Route::post('/products/{code}/trash', ProductTrashController::class)
    ->can(Ability::Trash->value, ProductInterface::class);
```

The standard resource abilities are:

- `viewAny`
- `view`
- `create`
- `update`
- `delete`
- `viewTrashed`
- `trash`
- `restore`

This keeps route protection uniform without forcing every installation to use
the same authentication model.

## Tests prove the convention

The `gildsmith/testing` package contains helpers for the shared contracts. Use
them so every package proves the same resource behavior in the same way.

Facade tests should verify the CRUD or trashable facade contract:

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

Route tests should verify that each resource route exists and is protected by
the expected ability:

```php
use Gildsmith\Contract\Product\ProductInterface;

itExposesTrashableResourceRoutes(
    uri: 'products',
    contract: ProductInterface::class,
);
```

Nested resources can name their route parameter explicitly:

```php
use Gildsmith\Contract\Product\AttributeValueInterface;

itExposesTrashableResourceRoutes(
    uri: 'attributes/{attribute}/values',
    contract: AttributeValueInterface::class,
    parameter: 'value',
);
```

The route helper checks the route surface and authorization middleware. It does
not replace controller tests for request validation, response shape, or facade
integration.

## Resource checklist

When adding a resource to a package, check that it has:

- A model contract for the domain concept.
- A model that implements the contract and `HasCodeInterface`.
- A unique indexed `code` column.
- Soft deletes when the resource should be trashable.
- A facade contract extending `CrudFacadeInterface` or
  `TrashableFacadeInterface`.
- A concrete facade bound in the package service provider.
- Routes protected with `ResourceAbility` and Laravel `can` middleware.
- Facade contract tests from `gildsmith/testing`.
- Route authorization tests from `gildsmith/testing` when the package exposes
  HTTP routes.
