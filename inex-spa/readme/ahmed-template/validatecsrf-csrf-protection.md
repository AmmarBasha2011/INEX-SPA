# @validateCsrf - CSRF Protection

The `@validateCsrf` directive in Ahmed PHP Template Engine helps protect forms and sensitive actions from Cross-Site Request Forgery (CSRF) attacks by validating CSRF tokens.

### Syntax

```php
@validateCsrf(token)
```

* `token`: The CSRF token to be validated.

### Description

* This directive checks if the provided CSRF token matches the expected value stored in the session.
* If the token is invalid, the request is rejected to prevent CSRF attacks.
* Ensures that form submissions and API requests originate from trusted sources.

### Example

```php
<form method="POST">
    @csrf // Generates a CSRF token
    <input type="hidden" name="csrf_token" value="@csrfToken">
    <button type="submit">Submit</button>
</form>

@validateCsrf($_POST['csrf_token'])
```

### Expected Behavior

* If the token is valid, the request proceeds normally.
* If the token is missing or invalid, an error is triggered to block the request.

### Notes

* Always include `@csrf` inside forms to generate a valid token.
* CSRF protection is essential for preventing unauthorized actions on behalf of users.
* Works best when combined with session-based authentication mechanisms.
