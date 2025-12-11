<?php

/**
 * Executes SQL queries from a file using PDO.
 *
 * This function connects to a MySQL database, reads an SQL file,
 * and executes the queries within it. The script will terminate on failure.
 *
 * @param string $host     The database host name or IP address.
 * @param string $user     The database user name.
 * @param string $password The database password.
 * @param string $database The name of the database.
 * @param string $filePath The file path to the SQL file to be executed.
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
