<?php
/**
 * Clear Database Tables Script
 *
 * This file defines and executes a utility to clear all tables from the
 * application's database. It is designed to be run from the command line,
 * typically via the `php ammar clear:db:tables` command.
 *
 * @warning Running this script directly will result in irreversible data loss.
 */

/**
 * Database Table Clearing Utility
 *
 * A destructive utility class designed to drop all tables from the database
 * configured in the .env file. This script is intended for development,
 * testing, or reset scenarios and should be used with extreme caution.
 * It is typically executed from the command line via the 'ammar' CLI tool.
 *
 * @package INEX\Database\Utils
 * @warning This script will cause irreversible data loss in the target database.
 */
class ClearDBTables
{
    /**
     * Executes the process of dropping all database tables.
     *
     * This method connects to the database, temporarily disables foreign key constraints,
     * retrieves a list of all table names, and executes a DROP TABLE command for each one.
     * After completion, it re-enables foreign key checks. Progress and error
     * messages are echoed directly to the console.
     *
     * @return void
     */
    public static function run()
    {
        $dbName = getEnvValue('DB_NAME');

        try {
            // Disable foreign key checks to allow dropping tables in any order.
            executeStatement('SET FOREIGN_KEY_CHECKS = 0;', [], false);

            // Get all table names from the current database.
            $query = executeStatement('SHOW TABLES;');
            if (!$query || !is_array($query)) {
                echo "✅ No tables found in database.\n";

                return;
            }

            // Extract table names into a simple array.
            $tables = [];
            foreach ($query as $row) {
                $tables[] = reset($row); // Get the first value of each row (the table name).
            }

            if (empty($tables)) {
                echo "✅ No tables found in database.\n";

                return;
            }

            // Drop each table found.
            foreach ($tables as $table) {
                if (!empty($table)) {
                    executeStatement("DROP TABLE `$table`;", [], false);
                    echo "🗑️ Deleted table: $table\n";
                }
            }

            // Re-enable foreign key checks as a good practice.
            executeStatement('SET FOREIGN_KEY_CHECKS = 1;', [], false);

            echo "🔥 All tables in database '$dbName' have been deleted!\n";
        } catch (Exception $e) {
            echo '❌ Error: '.$e->getMessage()."\n";
        }
    }
}

// Automatically execute the run method when this script is included.
ClearDBTables::run();
