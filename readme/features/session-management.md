# Session Management

You can use our session management like `$_SESSION` in `PHP` but in our framework:

```php
# Create a new session
Session::make('key', 'value');
# Example: Session::make('username', 'INEX SPA');

# Get a session value
Session::get('key');
# Example: Session::get('username');
# INEX SPA

# Delete a session
Session::delete('key');
# Example: Session::delete('username');
```
