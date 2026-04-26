<?php

/**
 * A PDO wrapper class for simplifying database connections and queries.
 *
 * This class provides a convenient way to connect to a database using
 * credentials stored in the .env file. It supports MySQL and SQLite.
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
     * The constructor reads the database configuration from environment variables.
     * If DB_DRIVER is 'sqlite', it connects to the SQLite database file.
     * Otherwise, it connects to a MySQL database.
     *
     * @param string $charset The character set for the database connection.
     *                        Defaults to 'utf8mb4'.
     */
    public function __construct($charset = 'utf8mb4')
    {
        $driver = getEnvValue('DB_DRIVER') ?: 'mysql';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            if ($driver === 'sqlite') {
                $dbFile = getEnvValue('DB_FILE') ?: 'database.sqlite';
                $this->pdo = new PDO("sqlite:$dbFile", null, null, $options);
            } else {
                $host = getEnvValue('DB_HOST');
                $dbname = getEnvValue('DB_NAME');
                $username = getEnvValue('DB_USER');
                $password = getEnvValue('DB_PASS');
                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
                $this->pdo = new PDO($dsn, $username, $password, $options);
            }
        } catch (PDOException $e) {
            exit('Database connection failed: '.$e->getMessage());
        }
    }

    /**
     * Prepares and executes an SQL statement with optional parameters.
     *
     * @param string $sql       The SQL query to execute.
     * @param array  $params    An array of values to bind.
     * @param bool   $is_return If `true`, returns all rows. If `false`, returns `true` on success.
     *
     * @return array|bool
     */
    public function query($sql, $params = [], $is_return = true)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $is_return ? $stmt->fetchAll() : true;
    }
}
