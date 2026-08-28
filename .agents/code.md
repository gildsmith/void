# Code

## Specification

- Treat approved contracts, tests, and documentation as the specification.
- Keep complexity behind the approved interface.
- Implement only the approved behavior and public surface.

## Facades

- A domain may expose multiple facades. Each facade owns one cohesive capability.
- Facades are the only operational entry points into a module; no code may call
  a module's internals directly.
- Keep facade methods and parameters minimal. Require only information callers
  inherently know.
- Express intent while hiding endpoints, configuration, processing, and
  implementation structure.
- Do not add public operations, parameters, or caller coordination for
  implementation convenience.

## Controllers

- Every controller must be invokable and declare a dedicated Request type.
- Controllers are decorators over facades: keep their logic minimal and leave
  domain work to the facade.
- When returning one model, return its API resource. When returning multiple
  models, return their resource collection.
- Controllers may return JSON responses for non-2xx outcomes.
- Do not return models or unwrapped model collections directly.

## Resources

- Every Laravel API resource must declare an `@mixin` for the domain model
  interface it serializes.
- Import the interface and use its short name in the annotation.
- Use `Resource::collection()` for model collections.
- Do not implement a collection resource unless it needs its own behavior or
  metadata.

## Conflicts

Do not quietly change an earlier decision to accommodate implementation. Return
to the relevant earlier step for review.
