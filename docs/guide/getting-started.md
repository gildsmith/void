# Getting Started

Use the `void` monorepo when contributing to Gildsmith core packages. It provides
a Laravel application with local path repositories for first-party packages.

## Install dependencies

```sh
composer install
npm install
```

## Run the application

```sh
composer run dev
```

## Work on packages

Official packages live in `packages/gildsmith/<package>`. Start new packages
from `packages/gildsmith/skeleton` so package structure and tooling stay
consistent.
