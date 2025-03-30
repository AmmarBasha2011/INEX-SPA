# @empty - Checking If a Variable Is Empty

The `@empty` directive in Ahmed PHP Template Engine is used to check whether a variable is empty (i.e., it is not set, `null`, `false`, or an empty string/array). This helps in conditionally displaying content based on the absence of a value.

### Syntax

```php
@empty(variable)
    // Code to execute if variable is empty
@endempty
```

* `variable` is the variable being checked.
* The enclosed block of code executes only if the variable is empty.

### Example

```php
@set(message, '')

@empty(message)
    <p>No message available.</p>
@endempty
```

### Output

```html
<p>No message available.</p>
```

### Notes

* `@empty` is useful for handling cases where a variable may be unset or contain an empty value.
* If the variable has a value, the enclosed block is skipped.
* Can be combined with `@isset` for advanced conditional checks.
