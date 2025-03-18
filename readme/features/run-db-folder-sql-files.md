# Run DB Folder SQL files

To run the migration `.sql` files located within the `db` folder, you need to execute the following command in any part of your application:

```php
runDB();
```

By executing this command, all SQL migration files will be processed, thereby updating your database schema as defined in the files within the `db` directory. This process ensures that your database structure aligns with the current version of your application, facilitating smooth transitions between different development stages.

Make sure that your database server is running and all necessary configurations are in place before executing the migration command to avoid potential errors or complications during
