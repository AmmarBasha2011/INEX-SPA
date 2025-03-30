# Session Management Directives

Ahmed PHP Template Engine provides multiple directives to manage session data: `@makeSession`, `@getSession`, and `@deleteSession`.

### @makeSession - Create a Session Variable

#### Syntax

```php
@makeSession(name, value)
```

* `name`: The session variable name.
* `value`: The value to store.

#### Example

```php
@makeSession("user", "Ahmed")
```

### @getSession - Retrieve a Session Variable

#### Syntax

```php
@getSession(name)
```

* `name`: The session variable name.

#### Example

```php
@var(username, @getSession("user"))
<p>Welcome, @username</p>
```

### @deleteSession - Delete a Session Variable

#### Syntax

```php
@deleteSession(name)
```

* `name`: The session variable name.

#### Example

```php
@deleteSession("user")
```

### Notes

* Sessions are stored on the server.
* `@makeSession` creates or updates a session variable.
* `@getSession` retrieves stored session data.
* `@deleteSession` removes a specific session variable.
