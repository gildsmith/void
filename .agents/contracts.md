# Contracts

Contracts live in `packages/gildsmith/contract/src`.

## Execution

- Add, edit, or remove interfaces in the contract package as needed.
- Continue without an approval pause when these rules settle the design.
- Ask when a cross-domain dependency is necessary or these rules leave an
  architectural choice unresolved.

## Layout

- Organize model and data interfaces by domain: `<Domain>/<Contract>`.
- End every interface name with `Interface`.
- Use at most one optional type directory: `<Domain>/<Type>/<Contract>`, such
  as `Product/Facades/ProductFacadeInterface`.
- Use the same type names and layout uniformly across domains.
- Use `Facades` for module entry points. Do not introduce `Gateway` or `API` as
  alternative names.
- Do not nest beyond domain and optional type without explicit approval.
- Define cross-package contracts here, not in feature packages.

## Shared contracts

- Check `Shared` before adding a contract.
- Share a capability when it is broadly useful or after about three meaningful
  repetitions, but only when reuse reduces more complexity than it introduces.
- CRUD, trashing, and coded IDs are good shared candidates. Minor similarities
  are not.
- Keep model-like shared contracts directly in `Shared`, such as
  `Shared/HasCodeInterface`.
- Group every other shared family by type, such as `Shared/Facades` or
  `Shared/Routing`; do not flatten them into one directory.
- Treat top-level `Models` and `Routing` as legacy. Move their contents into the
  appropriate `Shared` location when touching them, and add nothing new there.

## Dependencies

- Contracts are Laravel-specific and may use Laravel types when they are part of
  the intended API, such as `Collection`.
- Expose domain interfaces from facades. Do not include Eloquent `Model` in
  parameter or return types; implementations may still use Eloquent models.
- Declare every package used by a contract signature as a runtime dependency.
- Do not depend on implementation packages that already depend on `contract`.

## Facades

- A domain may expose multiple facades. Each facade should own one cohesive
  capability; there is no hard count limit.
- Prefer another facade when one facade would need unrelated operations or
  method prefixes to identify which resource it controls.
- Facades are the only operational entry points into a module. The rest of the
  application, including other modules, must not call module internals directly.
- Keep facade methods and parameters minimal. Require only information callers
  inherently know, such as a product ID.
- Express intent while hiding endpoints, configuration, processing steps, and
  implementation structure.
- Do not add public operations, parameters, or caller coordination for
  implementation convenience.

## Inputs

- Prefer typed data contracts whose accepted shape is explicit.
- Keep typed inputs in their owning domain and use the optional type level when
  grouping is useful. Choose a uniform group name, such as `Input` or `DTO`,
  from the first concrete design rather than settling it in the abstract.
- Avoid array inputs because they provide poor typing. The generic
  `CrudFacadeInterface` arrays are temporarily acceptable so it can serve
  different configurations; do not copy that flexibility into narrower APIs.

## Identifiers

- Every findable model must have a human-readable `code` that is unique within
  that model's dataset.
- Expose `code` as a publicly readable property. Do not require a getter method.
- Require the caller to supply `code` when the model is created; do not generate
  it inside the module.
- Keep `code` immutable for the model's lifetime. Consumers may retain it as a
  stable reference, so changing it would break the contract.
- Use `code` for searches, routes, and every human-facing reference.
- Database IDs are implementation details. Keep them out of human-facing
  surfaces and never require callers to use them for lookup.

## Exceptions

- A genuinely missing item is an expected result. Return `null` or `false`
  rather than throwing an exception solely for absence.
- Reserve exceptions for actual failures such as malformed input or queries,
  connectivity problems, and broken system invariants.
- Keep domain exceptions in `<Domain>/Exceptions` and prefer them over generic
  built-in exceptions at the domain boundary.
- Give every exception a descriptive name ending in `Exception`. Never use the
  bare name `Exception`; the suffix must have at least one word before it.
- Let the exception define its own message. Accept only the contextual data it
  needs, such as an ID or code, rather than a caller-supplied message.
- Build messages inside the exception with ordinary double-quoted strings,
  using interpolation or concatenation as appropriate. Do not use `printf` or
  `sprintf` formatting.
- Handle, recover from, or translate implementation exceptions inside the
  module when possible. Do not simply pass them through for callers to resolve.

## Domain boundaries

- Treat every domain as a separate service with owned data and a narrow public
  surface.
- Keep cross-domain relations minimal and expose only what another domain needs.
- Natural relationships such as order/payment or product/inventory do not
  justify unrestricted coupling.
- Ask before introducing a new or unavoidable cross-domain dependency; the
  enforcement approach is not yet settled.
