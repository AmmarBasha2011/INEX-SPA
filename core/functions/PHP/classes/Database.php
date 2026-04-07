<?php

/**
 * A PDO wrapper class for simplifying database connections and queries.
 *
 * This class provides a convenient way to connect to a MySQL or SQLite database
 * based on environment configuration.
 */
class Database
{
    /**
     * Holds the active PDO database connection instance.
     *
     * @var PDO|null
     */
    private $pdo;

    /**
     * Initializes the database connection.
     *
     * The constructor reads the database driver, host, name, user, and password
     * from environment variables, then establishes a PDO connection.
     */
    public function __construct($charset = 'utf8mb4')
    {
        $driver = getEnvValue('DB_DRIVER') ?: 'mysql';
        $host = getEnvValue('DB_HOST');
        $dbname = getEnvValue('DB_NAME');
        $username = getEnvValue('DB_USER');
        $password = getEnvValue('DB_PASS');

        if ($driver === 'sqlite') {
            $dsn = "sqlite:$dbname";
        } else {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            if ($driver === 'sqlite') {
                $this->pdo = new PDO($dsn, null, null, $options);
            } else {
                $this->pdo = new PDO($dsn, $username, $password, $options);
            }
        } catch (PDOException $e) {
            exit('Database connection failed: '.$e->getMessage());
        }
    }

    /**
     * Prepares and executes an SQL statement with optional parameters.
     */
    public function query($sql, $params = [], $is_return = true)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $is_return ? $stmt->fetchAll() : true;
    }
}
