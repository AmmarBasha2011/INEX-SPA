<?php

class Database
{
    private $pdo;

    public function __construct($charset = 'utf8mb4')
    {
        $host = getEnvValue('DB_HOST');
        $dbname = getEnvValue('DB_NAME');
        $username = getEnvValue('DB_USER');
        $password = getEnvValue('DB_PASS');

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            exit('Database connection failed: '.$e->getMessage());
        }
    }

    public function query($sql, $params = [], $is_return = true)
    {
        $startTime = microtime(true);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        if (getEnvValue('DEV_MODE') == 'true') {
            DevTools::addQuery($sql, $params, $executionTime);
        }

        return $is_return ? $stmt->fetchAll() : true;
    }
}

// Usage example:
// $db = new Database('localhost', 'test_db', 'root', '');
// $result = $db->query("SELECT * FROM users WHERE id = ?", [1]);
// print_r($result);
