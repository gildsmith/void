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
