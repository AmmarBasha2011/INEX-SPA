# @date - Date Formatting

The `@date` directive in Ahmed PHP Template Engine allows you to format dates in a human-readable way.

### Syntax

```php
@date(format, timestamp)
```

* `format`: The date format following PHP's `date()` function syntax.
* `timestamp`: (Optional) A Unix timestamp. If omitted, the current date and time are used.

### Example

```php
@set(currentTime, 1711825200) // Example Unix timestamp

<p>@date('Y-m-d', currentTime)</p>
<p>@date('d/m/Y H:i:s')</p>
```

### Output

```html
<p>2024-03-30</p>
<p>30/03/2024 14:00:00</p>
```

### Notes

* The format string follows PHP's `date()` function patterns.
* If no timestamp is provided, it defaults to the current time.
* Useful for displaying timestamps in a user-friendly format.
