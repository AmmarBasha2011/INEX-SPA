# @php - Executing Raw PHP Code

The `@php` directive in Ahmed PHP Template Engine allows you to write raw PHP code within your templates. This is useful for executing logic that cannot be easily expressed using template directives.

### Syntax

```php
@php
    // PHP code here
@endphp
```

* Any PHP code written inside `@php` and `@endphp` will be executed as standard PHP.

### Example

```php
@php
    $date = date('Y-m-d');
@endphp

<p>Today's date is {{ date }}</p>
```

### Output

```html
<p>Today's date is 2025-03-30</p>
```

### Notes

* Use `@php` only when necessary; template logic should ideally remain clean and minimal.
* Inside `@php`, you can define variables, perform calculations, and execute any PHP code.
* You can access PHP variables set within `@php` using `{{ variable }}` in the template.
