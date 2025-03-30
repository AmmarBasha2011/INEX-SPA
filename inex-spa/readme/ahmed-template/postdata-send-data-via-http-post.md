# @postData - Send Data via HTTP POST

The `@postData` directive in Ahmed PHP Template Engine allows you to send data using an HTTP POST request.

### Syntax

```php
@postData(url, data)
```

* `url`: The endpoint where the data will be sent.
* `data`: The data to be sent (associative array format).

### Description

* This directive makes an HTTP POST request to send data to a server.
* Useful for handling form submissions, API interactions, and background data processing.
* Works asynchronously to avoid page reloads.

### Example

```php
@postData("/submit", {"name": "Ahmed", "email": "ahmed@example.com"})
```

### Notes

* Ensure the target URL can handle POST requests.
* Supports dynamic values using template variables (e.g., `@postData("/api", {"user": @username})`).
* Can be used for AJAX-style requests within INEX SPA.
