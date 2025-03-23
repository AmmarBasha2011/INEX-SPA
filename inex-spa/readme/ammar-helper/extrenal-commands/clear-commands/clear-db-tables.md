# clear:db:tables

You can delete all tables in your database, the real tables not files in `db`for migrate. by single line command \`php ammar clear:db:tables\`.

Before running the command to delete all tables in your database, it's important to emphasize the significance of this action. The tables you're targeting are the actual database tables, not just files in the `db` directory used for migration purposes. This is a critical distinction to understand to avoid unintentional data loss.

To proceed, you can execute a single line command using PHP to clear all the real tables from your database. The syntax for the command is as follows:

```bash
php ammar clear:db:tables
```

Executing this command will irrevocably delete all existing tables in your database, leading to the loss of all data stored within them. Ensure you have comprehensive backups or are absolutely certain that you no longer need the data before proceeding.
