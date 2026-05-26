# Gildsmith Skeleton

This package is a starting point for new Gildsmith packages.

## Create a Package

Clone or fork this repository, then replace the skeleton identifiers with your
package name:

- `gildsmith/skeleton` in `composer.json`
- `Gildsmith\\Skeleton\\` namespace declarations
- `Gildsmith\\Skeleton\\Providers\\AppServiceProvider` in `composer.json`
- `Gildsmith\Skeleton\Providers\AppServiceProvider` in `testbench.yaml`

## Package Structure

- `src/Providers` registers package services, routes, and migrations.
- `src/Models` is for Eloquent models.
- `src/Facades` is for facade concrete implementations.
- `src/Controllers` is for route controllers.
- `src/Requests` is for form requests.
- `src/Resources` is for API resources.
- `database/migrations` is for package migrations.
- `database/factories` is for package model factories.
- `routes/api.php` is for API routes.
- `tests` contains Pest tests running through Orchestra Testbench.

## Development

```bash
composer install
composer test
composer lint
```

Gildsmith packages require PHP 8.4 or newer.
