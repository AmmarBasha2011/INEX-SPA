# @unless - Conditional Rendering for False Values

The `@unless` directive in Ahmed PHP Template Engine is used to execute a block of code only if a given condition evaluates to `false`. It is the opposite of `@if` and provides a cleaner way to check for negated conditions.

### Syntax

```php
@unless(condition)
    // Code to execute if condition is false
@endunless
```

* `condition` is the expression being evaluated.
* The enclosed block executes only if `condition` is `false`.

### Example

```php
@set(isAdmin, false)

@unless(isAdmin)
    <p>You do not have admin privileges.</p>
@endunless
```

### Output

```html
<p>You do not have admin privileges.</p>
```

### Notes

* `@unless` is a shorthand for `@if(!condition) ... @endif`.
* It makes templates more readable when checking for `false` conditions.
* Can be combined with `@else` for alternative logic handling.
