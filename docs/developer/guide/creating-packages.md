# Creating Packages

Gildsmith is built as a collection of Laravel packages. Most new functionality
should be built in packages, not directly in the root framework application. A
package should own a small domain, publish its Laravel service provider, and
expose stable contracts or facades when other packages need to talk to it.

## Choose a starting point

There are three practical ways to start a package.

### Clone the skeleton

Use `gildsmith/skeleton` when you want the current package structure with as
little decision-making as possible.

The skeleton exists in two places:

- `packages/gildsmith/skeleton` inside the `void` workspace.
- The split package repository at `github.com/gildsmith/skeleton`.

If you are already working inside `void`, copy the local package:

```sh
cp -R packages/gildsmith/skeleton packages/gildsmith/inventory
```

If you want to start from the split repository instead, fork or clone
`gildsmith/skeleton` directly and rename it for your package.

After copying it, update the package identity:

- Rename namespaces from `Gildsmith\Skeleton` to your package namespace.
- Change `composer.json` from `gildsmith/skeleton` to the new package name.
- Update the Laravel provider class in `extra.laravel.providers`.
- Rename factories, models, tests, and workbench classes that still mention the
  skeleton package.

This is the recommended path today because the skeleton already contains the
expected Laravel package conventions, Composer scripts, Testbench setup, and
autoload sections.

::: warning Keep package repositories separate
The `void` workspace can host your package while you develop it, but private or
project-specific packages should not be pushed to upstream `gildsmith/void`.
Fork `void` for your workspace and keep each package in its own repository when
it is ready to live independently.
:::

### Use Anvil

`gildsmith/anvil` is intended to become the package generator for Gildsmith. The
goal is for it to scaffold new packages into `packages/gildsmith/*` by default.

::: danger Not implemented yet
Anvil package scaffolding is not ready. Do not rely on it for creating packages
until the generator is implemented and documented here.
:::

When it exists, the flow should look roughly like this:

```sh
php artisan anvil:make-package inventory
```

The command above is illustrative. Treat it as design intent, not a working API.

### Build a Laravel package manually

At the end of the day, a Gildsmith package is still a Laravel package. You can
create one manually if you understand Laravel package discovery, service
providers, Composer autoloading, and local path repositories.

Good things to know before doing this:

- How Composer PSR-4 autoloading maps namespaces to directories.
- How Laravel discovers package service providers through `extra.laravel`.
- How migrations, routes, factories, and config are loaded from a package.
- How Orchestra Testbench boots package tests.

Laravel's package development documentation is the right baseline. Gildsmith adds
conventions on top, but it does not replace Laravel's package model.

## Minimum package shape

A first-party package should normally include:

```txt
composer.json
src/
src/Providers/AppServiceProvider.php
tests/
tests/Pest.php
tests/TestCase.php
database/factories/
database/migrations/
```

Not every package needs models, migrations, or routes. Keep the package focused:
add only the Laravel surface the domain actually needs.

Every Gildsmith package should explicitly require PHP 8.4 or newer:

```json
{
  "require": {
    "php": "^8.4"
  }
}
```

## Register the package

The root application uses Composer path repositories for local packages:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/*/*/"
    }
  ]
}
```

Require your package from the root application when you want to use it in the
local workbench:

```sh
composer require gildsmith/inventory:dev-master
```

Inside another Gildsmith package, add it to that package's `composer.json` when
it is a runtime dependency.

## Split repositories

First-party packages are developed together in `void`, then split to their own
repositories such as `gildsmith/product`, `gildsmith/support`, and
`gildsmith/skeleton`. You can work the same way for your own packages:

- Use `void` as the local integration workspace.
- Keep package code under `packages/<vendor>/<package>`.
- Keep the package's canonical history in its own repository.
- Require it through Composer when another package or application depends on it.

## Package checklist

Before calling a package ready, check that it can:

- Install with Composer.
- Autoload its namespace.
- Discover its Laravel service provider.
- Run its package tests from inside the package directory.
- Bind its contracts to concrete implementations when it provides a public API.
- Avoid leaking domain writes through controllers or models when a facade should
  own the workflow.
