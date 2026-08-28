# Tests

## Intent

- Test business requirements, not merely whether the code works.
- Cover relevant rules and non-happy paths. One successful example is not
  sufficient.
- Every test should identify the business requirement it protects.

## Layout

- Mirror the source directory structure under the test directory.
- Keep one test file per source file.
- Declare the source file with `covers()`.

## Organization

- Group every source method in its own `describe()` block.

## Expectations

- Use a separate `expect()` for every assertion. Never chain expectations.
