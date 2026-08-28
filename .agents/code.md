# Code

## Specification

- Treat approved contracts, tests, and documentation as the specification.
- Keep complexity behind the approved interface.
- Implement only the approved behavior and public surface.

## Controllers

- Controllers must return Laravel API resources or resource collections.
- Use a resource for one result and a resource collection for multiple results.
- Do not return models, arrays, booleans, JSON responses, or unwrapped
  collections directly.

## Conflicts

Do not quietly change an earlier decision to accommodate implementation. Return
to the relevant earlier step for review.
