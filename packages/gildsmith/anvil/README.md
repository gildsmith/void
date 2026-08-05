# Gildsmith Anvil

Anvil is the Gildsmith development-environment toolbox.

It provides console commands that smooth out common repository maintenance tasks
for developers working across the Gildsmith packages. The package is intended for
local development workflows only, such as keeping package dependencies aligned or
running repetitive setup chores from one place.

## Installation

Install Anvil as a development dependency:

```bash
composer require --dev gildsmith/anvil
```

## Commands

All Anvil commands must be namespaced with the `anvil:` prefix.

### Create Package

Create a new package scaffold from Anvil blueprint packages:

```bash
php artisan anvil:create-package
```

Anvil will ask for the vendor name, package name, and author name. The vendor
prompt defaults to `gildsmith`.

Blueprints live in `blueprints/packages`. Each directory is an atomic piece of a
package scaffold, such as `scaffolding`, `tests`, `pint`, or `github`. Anvil first
copies each blueprint package as-is, then renders only files ending in
`.blueprint` and removes the suffix. Blueprint variables use public camelCase names such as `{{ packageTitle }}`. Files ending in `.procedure` are reserved
for future procedural setup and are removed for now.

When adding new commands, keep them focused on making day-to-day development in
this repository easier.
