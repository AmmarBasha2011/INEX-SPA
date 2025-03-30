# @define - Define Variables

The `@define` directive in Ahmed PHP Template Engine allows you to define and store variables for later use within the template.

### Syntax

```php
@define(variable, value)
```

* `variable`: The name of the variable to define.
* `value`: The value assigned to the variable.

### Description

* This directive stores a value in a named variable that can be referenced elsewhere in the template.
* Useful for defining constants, reusable values, or computed data.

### Example

```php
@define(siteName, "Ahmed Engine")
@define(year, 2025)

<h1>Welcome to @siteName</h1>
<p>© @year All rights reserved.</p>
```

### Expected Behavior

* The defined variable is available throughout the template.
* It can be used in expressions, conditions, and loops.

### Notes

* Variables defined using `@define` persist within the template but do not carry over to different requests.
* Useful for improving template readability and avoiding redundant values.
* Can be combined with conditional statements and loops for dynamic content.
