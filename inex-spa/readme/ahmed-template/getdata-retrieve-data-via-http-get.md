# @getData - Retrieve Data via HTTP GET

The `@getData` directive in Ahmed PHP Template Engine allows you to fetch data using an HTTP GET request.

### Syntax

```php
@getData(url)
```

* `url`: The endpoint from which the data will be retrieved.

### Description

* This directive makes an HTTP GET request to fetch data from a specified source.
* Useful for retrieving API data, dynamic content, or query parameters.
* Works asynchronously to enhance performance.

### Example

```php
@var(response, @getData("/api/user?id=123"))
<p>Name: @response["name"]</p>
<p>Email: @response["email"]</p>
```

### Expected Output

```html
<p>Name: Ahmed</p>
<p>Email: ahmed@example.com</p>
```

### Notes

* Ensure the target URL supports GET requests.
* Works well with APIs and public endpoints.
* Supports dynamic parameters using template variables (e.g., `@getData("/api/user?id=@userId")`).
