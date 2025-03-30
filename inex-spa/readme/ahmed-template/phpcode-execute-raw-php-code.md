# @phpCode - Execute Raw PHP Code

The `@phpCode` directive in Ahmed PHP Template Engine allows you to execute raw PHP code directly within your template.

### Syntax

```php
@phpCode
    // Your PHP code here
@endphpCode
```

### Description

* This directive allows embedding PHP logic inside the template.
* Useful for complex operations that require PHP execution.
* Provides flexibility for dynamic content generation.

### Example

```php
@phpCode
    $year = date("Y");
@endphpCode

<p>Current Year: @year</p>
```

### Expected Output

```html
<p>Current Year: 2025</p>
```

### Notes

* Be cautious with direct PHP execution to avoid security risks.
* Prefer built-in Ahmed directives when possible.
* Supports standard PHP syntax within the `@phpCode` block.
