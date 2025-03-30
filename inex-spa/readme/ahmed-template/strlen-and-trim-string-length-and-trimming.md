# @strlen & @trim - String Length and Trimming

The `@strlen` and `@trim` directives in Ahmed PHP Template Engine allow you to work with string lengths and remove whitespace.

### Syntax

```php
@strlen(stringValue)
@trim(stringValue)
```

* `stringValue`: The string to be processed.

### Description

* `@strlen` returns the length of a string (number of characters).
* `@trim` removes whitespace from the beginning and end of a string.
* Useful for text processing, validation, and formatting.

### Example

#### Get String Length

```php
@var(name, "Ahmed")
<p>Length: @strlen(name)</p>
```

**Expected Output:**

```html
<p>Length: 5</p>
```

#### Trim Whitespace

```php
@var(text, "  Hello World  ")
<p>Trimmed: '@trim(text)'</p>
```

**Expected Output:**

```html
<p>Trimmed: 'Hello World'</p>
```

### Notes

* `@strlen` does not count whitespace separately.
* `@trim` is useful for cleaning up user input before processing.
* Works with variables and direct string values.
