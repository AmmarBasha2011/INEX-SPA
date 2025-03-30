# @number\_format - Number Formatting

The `@number_format` directive in Ahmed PHP Template Engine allows you to format numbers with thousands separators and decimal points for better readability.

### Syntax

```php
@number_format(number, decimals, decimal_separator, thousand_separator)
```

* `number`: The number to be formatted.
* `decimals`: The number of decimal places (optional, default is `0`).
* `decimal_separator`: The character used for the decimal point (optional, default is `.`).
* `thousand_separator`: The character used to separate thousands (optional, default is `,`).

### Example

```php
@set(price, 1234567.891)

<p>@number_format(price, 2, '.', ',')</p>
<p>@number_format(price, 0, '', '.')</p>
```

### Output

```html
<p>1,234,567.89</p>
<p>1.234.568</p>
```

### Notes

* Useful for displaying currency, large numbers, or percentage values.
* Default behavior rounds numbers to the nearest integer if `decimals` is not specified.
* Custom separators help match different international number formats.
