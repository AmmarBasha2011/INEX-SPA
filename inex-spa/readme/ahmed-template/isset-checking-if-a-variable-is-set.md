# @isset - Checking If a Variable Is Set

The `@isset` directive in Ahmed PHP Template Engine is used to check whether a variable has been defined and is not `null`. It allows conditional rendering based on the presence of a variable.

### Syntax

```php
@isset(variable)
    // Code to execute if variable is set
@endisset
```

* `variable` is the variable being checked.
* The enclosed block of code executes only if the variable exists and is not `null`.

### Example

```php
@set(name, 'Ammar')

@isset(name)
    <p>Hello, {{ name }}!</p>
@endisset
```

### Output

```html
<p>Hello, Ammar!</p>
```

### Notes

* `@isset` is useful for ensuring variables exist before displaying them.
* If the variable is not set, the enclosed block is skipped.
* Can be combined with `@else` or `@empty` for alternative behavior.
