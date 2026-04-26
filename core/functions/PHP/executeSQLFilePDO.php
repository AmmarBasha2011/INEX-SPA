<?php

/**
 * Connects to a database and executes a series of SQL queries from a specified file.
 *
 * This function is typically used for running database migrations or seeding the database.
 * It supports both MySQL and SQLite based on the DB_DRIVER environment variable.
 *
 * @param string $host     The hostname (MySQL).
 * @param string $user     The username (MySQL).
 * @param string $password The password (MySQL).
 * @param string $database The name of the database (MySQL).
 * @param string $filePath The full path to the .sql file.
 *
 * @return void
 */
function executeSQLFilePDO($host, $user, $password, $database, $filePath)
{
    try {
        $driver = getEnvValue('DB_DRIVER') ?: 'mysql';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        if ($driver === 'sqlite') {
            $dbFile = getEnvValue('DB_FILE') ?: 'database.sqlite';
            $pdo = new PDO("sqlite:$dbFile", null, null, $options);
        } else {
            $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $password, $options);
        }

        // Read SQL file
        $sqlContent = file_get_contents($filePath);
        if ($sqlContent === false) {
            throw new Exception('Error reading SQL file.');
        }

        // Split SQL statements and execute them
        $queries = explode(';', $sqlContent);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }
    } catch (PDOException $e) {
        exit('Database error: '.$e->getMessage());
    } catch (Exception $e) {
        exit('Error: '.$e->getMessage());
    }
}
