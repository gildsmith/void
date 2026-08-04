# Gildsmith Auth

This package provides the authentication and authorization layer for Gildsmith.

## Purpose

Gildsmith packages expose protected routes through Laravel's authorization
system. This package is the home for the default authentication and policy
implementation that makes those routes usable in a complete Gildsmith
application.

The first version is intentionally small. It starts as a Laravel package shell
so the actual auth decisions can be added deliberately.

## Package Structure

- `src/Providers` registers package services, routes, and migrations.
- `src/Models` is for Eloquent models.
- `src/Facades` is for facade concrete implementations.
- `src/Controllers` is for route controllers.
- `src/Requests` is for form requests.
- `src/Resources` is for API resources.
- `src/Policies` is for default Gildsmith policies.
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
