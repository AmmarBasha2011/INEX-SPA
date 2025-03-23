# Database Connection

Now, you can connect to database.

* First, update .env

```ini
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=inexspa
DB_USE=true
```

* Second, use in any part in application

```php
<?php
print_r(executeStatement("SELECT * FROM users WHERE id = ?", [1], true)) // SQL, Params=[], Is Return=true
?>
```
