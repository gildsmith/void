# Core Packages

Several Gildsmith packages are not domains by themselves. They provide the
language, shared behavior, and testing tools used by domain packages.

## Contract

`gildsmith/contract` contains interfaces for commerce concepts and package APIs.
Use it when another package needs to depend on a capability without knowing the
implementation.

For example, product facade contracts live under:

```txt
Gildsmith\Contract\Facades\Product
```

The general facade hierarchy is:

```txt
ProductFacadeInterface
-> TrashableFacadeInterface
-> CrudFacadeInterface
```

That means a product facade must satisfy the product-specific API, trashable
operations, and the generic CRUD surface.

## Support

`gildsmith/support` contains reusable implementation helpers. It is the right
place for behavior that multiple packages can share but that does not belong in
the contract package.

Examples include:

- Laravel facade classes such as `Gildsmith\Support\Facades\Product`.
- Shared model concerns.
- Validation helpers.
- Traits for implementation-level checks.

Support code may throw concrete exceptions and may depend on Laravel
implementation details. Contract code should stay focused on interfaces.

## Testing

`gildsmith/testing` contains reusable Pest helpers for contract-style tests.
These helpers let a package prove that a concrete implementation satisfies a
shared Gildsmith contract.

For example:

```php
itFulfillsTrashableFacadeContract(
    facade: ProductFacade::class,
    model: Product::class,
);
```

The helper uses the model factory to create valid records, then exercises the
facade through the public contract methods.

## Product

`gildsmith/product` is a domain package. It provides product catalog models,
migrations, controllers, factories, and facade implementations.

Gildsmith development should usually produce more packages like this. The root
Laravel application is a workbench and integration point; domain features belong
in focused packages so they can be installed, tested, replaced, and versioned
independently.

Domain packages should use the core packages like this:

- Define or reuse interfaces from `gildsmith/contract`.
- Put reusable traits and cross-package helpers in `gildsmith/support`.
- Test contract compliance with `gildsmith/testing`.
- Keep package-specific models, migrations, controllers, and facade concretes in
  the domain package.
