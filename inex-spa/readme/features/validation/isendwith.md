# isEndWith

`Validation` class provide `isEndWith` function

## Syntax

```php
Validation::isEndWith("text", ["list", "of", "words"])
```

## Example

```php
echo Validation::isEndWith("ammarFromEgypt", ["usa", "libya"]) // false
echo Validation::isEndWith("ammarFromEgypt", ["egypt", "usa"]) // true
```
