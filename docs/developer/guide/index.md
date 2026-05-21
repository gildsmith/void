# Introduction

Gildsmith is a Laravel-based ecommerce framework for building modular commerce
applications. The framework is maintained as a monorepo and split into focused
packages that can be installed independently.

## What Gildsmith provides

- Contracts that describe core commerce concepts.
- Domain packages for product catalog functionality.
- Support utilities that keep shared package behavior consistent.
- A local Laravel application for developing and testing first-party packages.

## Repository layout

The `void` monorepo contains the Laravel workbench app at the repository root and
first-party packages in `packages/gildsmith/*`.

## What to read next

- [Getting Started](/guide/getting-started) explains how to install the monorepo
  and run the local Laravel application.
- [Creating Packages](/guide/creating-packages) shows the current options for
  starting a new Gildsmith package.
- [Resource Architecture](/guide/resource-architecture) explains the code,
  facade, ability, and testing conventions used by resource packages.
- [Core Packages](/guide/core-packages) explains the role of `contract`,
  `support`, and `testing`.
- [Facades and Contracts](/guide/facades-and-contracts) documents the CRUD facade
  pattern used by first-party packages.
