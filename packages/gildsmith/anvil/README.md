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

For example, a command named `composer` should be executed as:

```bash
php artisan anvil:composer
```

When adding new commands, keep them focused on making day-to-day development in
this repository easier.
