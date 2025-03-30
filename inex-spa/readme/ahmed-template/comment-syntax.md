# {-- Comment Syntax --}

Ahmed PHP Template Engine supports inline comments using `{-- --}` syntax. These comments are ignored during template rendering and are useful for adding explanations within your templates.

### Syntax

```php
{-- This is a comment --}
```

### Description

* The `{-- --}` syntax allows you to add comments inside your templates.
* These comments will not be included in the rendered output.
* Useful for documentation and debugging purposes.

### Example

```php
{-- This section displays the website title --}
<h1>@siteName</h1>

{-- TODO: Add more details here --}
<p>Welcome to our website!</p>
```

### Expected Behavior

* The comments are completely ignored during parsing.
* They do not appear in the final HTML output.

### Notes

* Unlike HTML comments (`<!-- -->`), Ahmed template comments are not visible in the page source.
* Use them to improve code readability without affecting performance.
