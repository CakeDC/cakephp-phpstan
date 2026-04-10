# Changelog

## 4.2.0 (2026-04-10)

### New Features

- **`SelectQueryFindListReturnTypeExtension`**: Provides proper return type inference for `find('list')->toArray()`, returning `array<int|string, string>` instead of the generic entity array type. Works with chained queries (`where()`, `orderBy()`, `limit()`, etc.).
- **`groupField` support for `find('list')`**: When `groupField` is provided to `find('list')`, the return type is correctly inferred as `array<int|string, array<int|string, string>>`.
- **Custom application namespace**: Support for defining a custom application namespace via the `cakeDC.appNamespace` configuration parameter (defaults to `App`).

### Improvements

- Made constructor dependency in rules/extensions optional to preserve backwards compatibility; instances self-instantiate if none is provided.

## 4.1.1 (2025-11-13)

### Improvements

- `DisallowEntityArrayAccessRule`: ignore custom (non-integer) array access keys, allowing legitimate use cases.

## 4.1.0

### New Features

- Added `ControllerMethodMustBeUsedRule`: enforces that `render()` and `redirect()` must be used (returned or assigned) to prevent unreachable code.

## 4.0.0

### Breaking Changes

- Requires CakePHP 5.x and PHPStan 2.x.

## 3.x

See git history for older changes.
