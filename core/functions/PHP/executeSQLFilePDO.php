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
        // Connect to MySQL with PDO
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

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
