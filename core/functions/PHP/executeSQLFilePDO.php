<?php

/**
 * Connects to a MySQL database and executes a series of SQL queries from a specified file.
 *
 * This function is typically used for running database migrations or seeding the database.
 * It reads an SQL file, splits its content into individual queries (delimited by semicolons),
 * and executes each one. The script will terminate and display an error message if the
 * database connection, file reading, or a query execution fails.
 *
 * @param string $host     The hostname or IP address of the database server.
 * @param string $user     The username for the database connection.
 * @param string $password The password for the database connection.
 * @param string $database The name of the database to connect to.
 * @param string $filePath The full path to the .sql file containing the queries to be executed.
 *
 * @return void
 */
function executeSQLFilePDO($host, $user, $password, $database, $filePath)
{
    try {
        // SECURITY: Validate file path to prevent path traversal
        $realFilePath = realpath($filePath);
        if ($realFilePath === false) {
            throw new Exception('SQL file not found.');
        }
        // SECURITY: Ensure file is within allowed directories
        $allowedDirs = [realpath(__DIR__.'/../../../db'), realpath(__DIR__.'/../db')];
        $isAllowed = false;
        foreach ($allowedDirs as $dir) {
            if ($dir !== false && strpos($realFilePath, $dir) === 0) {
                $isAllowed = true;
                break;
            }
        }
        if (!$isAllowed) {
            throw new Exception('Access denied: SQL file outside allowed directories.');
        }

        $driver = getEnvValue('DB_DRIVER');
        if ($driver === 'sqlite') {
            $dsn = "sqlite:$database";
        } else {
            $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        }

        // Connect to DB with PDO
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        // Read SQL file
        $sqlContent = file_get_contents($realFilePath);
        if ($sqlContent === false) {
            throw new Exception('Error reading SQL file.');
        }

        // Split SQL statements and execute them
        $queries = explode(';', $sqlContent);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                // SECURITY: Only allow safe SQL statements (no DROP, DELETE, TRUNCATE)
                $firstWord = strtoupper(strtok($query, " \t\n
\0\x0B"));
                $allowedFirstWords = ['CREATE', 'ALTER', 'INSERT', 'UPDATE', 'SELECT', 'BEGIN', 'COMMIT', 'START'];
                if (!in_array($firstWord, $allowedFirstWords)) {
                    error_log("Skipping unsafe SQL statement: $query");
                    continue;
                }
                $pdo->exec($query);
            }
        }
    } catch (PDOException $e) {
        error_log('Database error: '.$e->getMessage());
        if (getEnvValue('DEV_MODE') === 'true') {
            exit('Database error: '.$e->getMessage());
        }
        exit('Database error occurred.');
    } catch (Exception $e) {
        error_log('Error: '.$e->getMessage());
        if (getEnvValue('DEV_MODE') === 'true') {
            exit('Error: '.$e->getMessage());
        }
        exit('Error occurred.');
    }
}
