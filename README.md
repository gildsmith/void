<p align="center"><img src="https://raw.githubusercontent.com/gildsmith/art/refs/heads/master/gildsmith/logo.svg" width="400" alt="Gildsmith Logo"></p>


Gildsmith is a modern, Laravel-based ecommerce framework designed to replace
legacy platforms with a clean, modular, and developer-first architecture.

Where traditional systems grow rigid and fragile over time, Gildsmith emphasises:
- Composable packages instead of monolithic cores
- Predictable, framework-native development patterns
- Clear separation of concerns
- Simple, maintainable domain logic
- First-class testing and upgradeability

## \>void
## Preface to this repo

Void is the official Gildsmith monorepo used for core development, package
creation, and ecosystem maintenance. provides a ready-to-use Laravel environment
with all first-party packages loaded locally for rapid iteration.

**Void is not a production application and should not be deployed as such.**

## Contribution Guide

### All development happens inside `/packages`

Every official Gildsmith component lives under `packages/gildsmith/<package>`.
These directories mirror to individual repos automatically.

Therefore:
- Submit PRs to this repo only, never to the mirrored package repos.
- Avoid adding project-specific or client-specific modules to this monorepo.

Community modules live elsewhere [pending].

### When creating packages, use `skeleton`

Start with the `gildsmith/skeleton` package. If something is missing, improve
the skeleton rather than diverging from it.
