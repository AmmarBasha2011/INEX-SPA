<?php

/**
 * Provides a utility to destructively clear all tables from the configured database.
 *
 * This class is designed for development and testing environments to quickly reset
 * the database schema. It connects to the database specified in the .env file,
 * retrieves a list of all tables, and drops them.
 *
 * @warning This is a highly destructive operation and will result in permanent data loss.
 *          Do not use in a production environment.
 */
class ClearDBTables
{
    /**
     * Connects to the database and drops all existing tables.
     *
     * The method performs the following steps:
     * 1. Disables foreign key checks to avoid dependency errors.
     * 2. Fetches a list of all table names from the database.
     * 3. Iterates through the list and executes a `DROP TABLE` command for each one.
     * 4. Re-enables foreign key checks.
     *
     * It outputs progress messages to the console for each table dropped and a final
     * success or error message.
     *
     * @return void
     */
    public static function run()
    {
        $driver = getEnvValue('DB_DRIVER');
        $dbName = $driver === 'sqlite' ? getEnvValue('DB_FILE') : getEnvValue('DB_NAME');

        try {
            // Disable foreign key checks for MySQL
            if ($driver !== 'sqlite') {
                executeStatement('SET FOREIGN_KEY_CHECKS = 0;', [], false);
            }

            // Fetch table list
            $query = self::getTableList($driver);

            if (!$query || !is_array($query)) {
                echo "✅ No tables found in database.\n";

                return;
            }

            // Extract table names
            $tables = self::extractTableNames($query);

            if (empty($tables)) {
                echo "✅ No tables found in database.\n";

                return;
            }

            // Drop tables
            self::dropTables($tables, $driver);

            // Re-enable foreign key checks for MySQL
            if ($driver !== 'sqlite') {
                executeStatement('SET FOREIGN_KEY_CHECKS = 1;', [], false);
            }

            echo "🔥 All tables in database '$dbName' have been deleted!\n";
        } catch (Exception $e) {
            error_log('ClearDBTables error: '.$e->getMessage());
            echo '❌ Error: An internal error occurred.'.PHP_EOL;
        }
    }

    /**
     * Retrieves a list of all tables in the database.
     *
     * @param string $driver The database driver (sqlite or mysql).
     *
     * @return array|false The query result array, or false on failure.
     */
    private static function getTableList($driver)
    {
        if ($driver === 'sqlite') {
            return executeStatement("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';");
        }

        return executeStatement('SHOW TABLES;');
    }

    /**
     * Extracts table names from a query result set.
     *
     * @param array $query The query result rows.
     *
     * @return array An array of table name strings.
     */
    private static function extractTableNames($query)
    {
        $tables = [];
        foreach ($query as $row) {
            $tables[] = reset($row); // Get the first value of each row
        }

        return array_filter($tables); // Remove empty values
    }

    /**
     * Drops a list of tables from the database.
     *
     * @param array  $tables The list of table names to drop.
     * @param string $driver The database driver (sqlite or mysql).
     *
     * @return void
     */
    private static function dropTables($tables, $driver)
    {
        foreach ($tables as $table) {
            if (!empty($table)) {
                if ($driver === 'sqlite') {
                    executeStatement("DROP TABLE IF EXISTS `$table`;", [], false);
                } else {
                    executeStatement("DROP TABLE `$table`;", [], false);
                }
                echo "🗑️ Deleted table: $table\n";
            }
        }
    }
}
