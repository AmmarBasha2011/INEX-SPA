# @getEnv

The `@getEnv` directive in Ahmed Template Engine for INEX SPA is used to retrieve environment variables from the `.env` file, making it easy to access configuration settings dynamically.

### Syntax

```php
@getEnv('VARIABLE_NAME')
```

#### Example:

```php
<p>Database Host: @getEnv('DB_HOST')</p>
```

If `DB_HOST` is set in the `.env` file as:

```ini
DB_HOST=localhost
```

The output will be:

```php
<p>Database Host: localhost</p>
```

### Securing Sensitive Data

Since `@getEnv` retrieves values directly from the environment, it is recommended to store sensitive data like API keys and database credentials in the `.env` file instead of hardcoding them into templates.

### Conclusion

The `@getEnv` directive simplifies access to environment variables, ensuring better configuration management and security in Ahmed Template Engine.
