# @checkRateLimit - Rate Limiting Requests

The `@checkRateLimit` directive in Ahmed PHP Template Engine allows you to limit the number of requests a user can make within a specific time frame to prevent abuse.

### Syntax

```php
@checkRateLimit(key, maxRequests, timeWindow)
```

* `key`: A unique identifier for the request source (e.g., IP address, user ID).
* `maxRequests`: The maximum number of allowed requests within the given time window.
* `timeWindow`: The time frame in seconds for rate limiting.

### Description

* This directive checks if the given `key` has exceeded the allowed request limit.
* If the limit is exceeded, further requests are blocked until the time window resets.
* Helps prevent spam, brute-force attacks, and excessive API usage.

### Example

```php
@checkRateLimit('user_123', 10, 60)
```

### Expected Behavior

* If `user_123` makes more than 10 requests within 60 seconds, further requests will be denied.
* The limit resets after the time window expires.
* Can be applied globally or to specific sections of the template.

### Notes

* Ensure the directive is used in performance-sensitive areas.
* Works best when combined with logging and error handling.
* Can be configured dynamically for different user roles.
