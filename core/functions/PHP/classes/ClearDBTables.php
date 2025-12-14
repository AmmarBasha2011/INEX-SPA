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
        $dbName = getEnvValue('DB_NAME');

        try {
            // Disable foreign key checks
            executeStatement('SET FOREIGN_KEY_CHECKS = 0;', [], false);

            // Get all table names
            $query = executeStatement('SHOW TABLES;');
            if (!$query || !is_array($query)) {
                echo "✅ No tables found in database.\n";

                return;
            }

            // Extract table names
            $tables = [];
            foreach ($query as $row) {
                $tables[] = reset($row); // Get the first value of each row
            }

            if (empty($tables)) {
                echo "✅ No tables found in database.\n";

                return;
            }

            // Drop tables one by one
            foreach ($tables as $table) {
                if (!empty($table)) {
                    executeStatement("DROP TABLE `$table`;", [], false);
                    echo "🗑️ Deleted table: $table\n";
                }
            }

            // Re-enable foreign key checks
            executeStatement('SET FOREIGN_KEY_CHECKS = 1;', [], false);

            echo "🔥 All tables in database '$dbName' have been deleted!\n";
        } catch (Exception $e) {
            echo '❌ Error: '.$e->getMessage()."\n";
        }
    }
}

