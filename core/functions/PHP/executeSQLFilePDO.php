<?php

/**
 * Executes a SQL file using PDO.
 *
 * @param string $host     The database host.
 * @param string $user     The database user.
 * @param string $password The database password.
 * @param string $database The database name.
 * @param string $filePath The path to the SQL file.
 */
function executeSQLFilePDO($host, $user, $password, $database, $filePath)
{
    try {
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $sqlContent = file_get_contents($filePath);
        if ($sqlContent === false) {
            throw new Exception('Error reading SQL file.');
        }

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
