# Cleanup

## Imports

Import every referenced class, including PHP-native classes, and use its short
name in code and PHPDoc. Do not use fully qualified class names inline.

## PHPDoc property descriptions

Put the description on the next line with one extra space.

Correct:

```php
* @property string $name
*  Human-readable name.
```

Wrong — description after the variable:

```php
* @property string $name Human-readable name.
```

Wrong — description aligned with the variable:

```php
* @property string $name
*                        Human-readable name.
```

## Scope

Review changed and immediately related code for the same style problems. Do not
change behavior during cleanup; return to the relevant earlier step instead.
