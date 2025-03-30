# @substr - Extract a Substring

The `@substr` directive in Ahmed PHP Template Engine is used to extract a portion of a string based on a starting position and optional length.

### Syntax

```php
@substr(variable, start, length)
```

* `variable`: The string to extract from.
* `start`: The position to begin extraction (0-based index).
* `length` _(optional)_: The number of characters to extract.

### Description

* If `length` is omitted, it extracts everything from `start` to the end of the string.
* Supports negative indices: a negative `start` extracts from the end of the string.

### Example

```php
@define(text, "Hello, World!")

<p>@substr(text, 7, 5)</p>
<p>@substr(text, -6, 5)</p>
```

### Expected Output

```html
<p>World</p>
<p>World</p>
```

### Notes

* Useful for manipulating strings dynamically within templates.
* Negative `start` counts from the end of the string.
* If `length` is longer than the remaining string, it extracts until the end.
