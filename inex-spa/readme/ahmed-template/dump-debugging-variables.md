# @dump - Debugging Variables

The `@dump` directive in Ahmed PHP Template Engine allows you to output detailed information about variables for debugging purposes. It is similar to PHP's `var_dump()` but formatted for better readability within the template.

### Syntax

```php
@dump(variable)
```

* `variable` is the variable you want to inspect.
* It prints the value and type of the variable directly within the template.

### Example

```php
@set(user, ['name' => 'Ammar', 'age' => 13])
@dump(user)
```

### Output

```html
array(
    "name" => "Ammar",
    "age" => 13
)
```

### Notes

* `@dump` is useful for debugging arrays, objects, and other complex data structures.
* It should be removed in production templates to avoid exposing internal data.
* Can be combined with `@set` for testing variable values.
