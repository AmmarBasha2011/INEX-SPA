<?php
class ClearDBTables {
    public static function run() {
        $dbName = getEnvValue('DB_NAME');

        try {
            // Disable foreign key checks
            executeStatement("SET FOREIGN_KEY_CHECKS = 0;", [], false);

            // Get all table names
            $query = executeStatement("SHOW TABLES;");
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
            executeStatement("SET FOREIGN_KEY_CHECKS = 1;", [], false);

            echo "🔥 All tables in database '$dbName' have been deleted!\n";
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}

ClearDBTables::run();
