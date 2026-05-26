# Installation

Gildsmith is designed as a package-oriented Laravel application. For core
development, install the `void` monorepo and work on packages from there. For a
new application, start from a clean Laravel project or the Gildsmith workspace
rather than dropping packages into an existing production app.

## Existing Laravel applications

::: danger Users table ownership
`gildsmith/auth` owns the `users` table.

Its migration drops any existing `users` table and recreates it as the
Gildsmith identity table. Do not install or migrate `gildsmith/auth` in an
existing Laravel application unless you have backed up the database and are
ready to replace that table.
:::

Gildsmith Auth is intentionally opinionated because identity is foundational to
the rest of the framework. The package provides the shared authentication
identity used by customer and employee actors, so it expects to control the
shape of the `users` table.

If you are evaluating Gildsmith inside an existing Laravel codebase, use a
separate database or a disposable local environment first.

## Package migrations

Gildsmith packages ship their own migrations. When a package owns a core table,
its migrations should be treated as authoritative for that table.

Run migrations only after reviewing the packages you have installed:

```sh
php artisan migrate
```

For package development, prefer running tests from inside the package:

```sh
cd packages/gildsmith/auth
composer test
```
