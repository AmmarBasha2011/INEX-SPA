# isStartWith

`Validation` class provide `isStartWith` function

## Syntax

```php
Validation::isStartWith("text", ["list", "of", "words"])
```

## Example

```php
echo Validation::isStartWith("ammarFromEgypt", ["yousef", "yahya"]) // false
echo Validation::isStartWith("ammarFromEgypt", ["ammar", "yousef"]) // true
```
