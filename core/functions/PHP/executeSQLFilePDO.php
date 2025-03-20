<?php
function executeSQLFilePDO($host, $user, $password, $database, $filePath) {
    try {
        // Connect to MySQL with PDO
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // Read SQL file
        $sqlContent = file_get_contents($filePath);
        if ($sqlContent === false) {
            throw new Exception("Error reading SQL file.");
        }

        // Split SQL statements and execute them
        $queries = explode(";", $sqlContent);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>