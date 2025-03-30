# isTextLength

`Validation` class provide `isTextLength` function

## Syntax

```php
Validation::isTextLength("text", "maxLength")
```

## Example

```php
echo Validation::isTextLength("Ammar", 3) // false
echo Validation::isTextLength("Ammar", 10) // true
```
