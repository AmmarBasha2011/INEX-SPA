# String Case Conversion - @strtoupper, @strtolower, and @ucfirst

The `@strtoupper`, `@strtolower`, and `@ucfirst` directives in Ahmed PHP Template Engine allow you to modify the case of text dynamically.

### Syntax

```php
@strtoupper(text)
@strtolower(text)
@ucfirst(text)
```

* `@strtoupper` converts the given `text` to uppercase.
* `@strtolower` converts the given `text` to lowercase.
* `@ucfirst` capitalizes the first letter of the given `text`.

### Example

```php
@set(name, 'ammar')

<p>@strtoupper(name)</p>
<p>@strtolower(name)</p>
<p>@ucfirst(name)</p>
```

### Output

```html
<p>AMMAR</p>
<p>ammar</p>
<p>Ammar</p>
```

### Notes

* Useful for formatting text dynamically in templates.
* Works with both variables and static strings.
* Can be combined with other directives for more complex string manipulations.
