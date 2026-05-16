# Getting Started

Use the `void` monorepo when contributing to Gildsmith core packages or building
your own Gildsmith packages. It provides a Laravel application with local path
repositories so packages can be developed together without publishing them first.

## Fork the workspace

If you are building your own packages, fork `gildsmith/void` first and clone your
fork:

```sh
git clone git@github.com:your-vendor/void.git gildsmith-void
cd gildsmith-void
```

Keep your package work in your fork or in separate package repositories. Do not
push private or project-specific packages back to the upstream `gildsmith/void`
repository.

## Install dependencies

Gildsmith packages require PHP 8.4 or newer.

```sh
composer install
npm install
```

## Run the application

```sh
composer run dev
```

## Work on packages

Official packages live in `packages/gildsmith/<package>`. Your own packages can
live in the same `packages` workspace while you develop them, but they should be
committed to your own repository.

Gildsmith is package-oriented. When you add a new feature, prefer creating or
extending a package instead of putting domain code into the root Laravel
application. The root application is a workbench for composing and testing
packages.

## Run package tests

Most package work should be verified from inside the package itself:

```sh
cd packages/gildsmith/product
composer test
```

Package tests use Orchestra Testbench so each package can behave like a Laravel
package without needing to boot the full root application.
