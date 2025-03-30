# @toJson & @fromJson - JSON Conversion

The `@toJson` and `@fromJson` directives in Ahmed PHP Template Engine allow you to convert data to and from JSON format.

### Syntax

```php
@toJson(data)
@fromJson(jsonString)
```

* `data`: The PHP array or object to be converted into a JSON string.
* `jsonString`: The JSON string to be converted into a PHP array.

### Description

* `@toJson` converts an array or object into a JSON-encoded string.
* `@fromJson` converts a JSON string back into a PHP array.
* Useful for handling API responses, data storage, and dynamic content manipulation.

### Example

#### Convert PHP Array to JSON

```php
@var(user, {"name": "Ahmed", "age": 25})
<p>@toJson(user)</p>
```

**Expected Output:**

```json
{"name": "Ahmed", "age": 25}
```

#### Convert JSON String to PHP Array

```php
@var(jsonData, '{"product": "Laptop", "price": 1200}')
@var(parsedData, @fromJson(jsonData))
<p>Product: @parsedData["product"]</p>
<p>Price: @parsedData["price"]</p>
```

**Expected Output:**

```html
<p>Product: Laptop</p>
<p>Price: 1200</p>
```

### Notes

* `@toJson` ensures proper JSON formatting for data exchange.
* `@fromJson` automatically decodes valid JSON into an array.
* Ensure the JSON string is properly formatted to avoid errors.
