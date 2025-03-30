# @runDB - Execute Database Migrations

The `@runDB` directive in Ahmed PHP Template Engine allows you to execute all `.sql` migration files in the `db` directory automatically.

### Syntax

```php
@runDB()
```

### Description

* This directive finds all `.sql` files inside the `db` folder and runs them sequentially.
* Useful for applying database migrations without manually executing SQL commands.

### Example

```php
@runDB()
```

### Expected Behavior

* All SQL files in the `db` folder are executed in order.
* If a file fails, execution may stop, depending on the database settings.
* Typically used at the beginning of an application to ensure the database is up to date.

### Notes

* Make sure the `db` folder contains valid `.sql` files.
* Ensure proper database permissions for execution.
* Can be used in combination with other database-related directives in Ahmed PHP Template Engine.
