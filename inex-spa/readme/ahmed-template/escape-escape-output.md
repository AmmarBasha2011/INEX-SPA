# @escape - Escape Output

The `@escape` directive in Ahmed PHP Template Engine is used to prevent HTML injection by escaping special characters in output.

### Syntax

```php
@escape(variable)
```

* `variable`: The value to be escaped before rendering.

### Description

* This directive ensures that output is displayed as plain text, preventing execution of embedded HTML or JavaScript.
* Helps protect against Cross-Site Scripting (XSS) attacks.
* Automatically converts special characters (`<`, `>`, `&`, `"`, etc.) into their HTML entities.

### Example

```php
@define(userInput, "<script>alert('Hacked!')</script>")

<p>@escape(userInput)</p>
```

### Expected Behavior

* Instead of executing the script, the output will be:

```html
<p>&lt;script&gt;alert('Hacked!')&lt;/script&gt;</p>
```

* This ensures that the script is displayed as text rather than being executed.

### Notes

* Use `@escape` when displaying user-generated content.
* Can be combined with other sanitization methods for added security.
* If you need raw output, avoid using this directive.
