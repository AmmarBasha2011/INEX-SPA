<?php

/**
 * A database connection and query class using PDO.
 */
class Database
{
    /**
     * The PDO instance.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Creates a new Database instance.
     *
     * @param string $charset The character set to use for the connection.
     */
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

    /**
     * Executes a SQL query.
     *
     * @param string $sql       The SQL query to execute.
     * @param array  $params    The parameters to bind to the query.
     * @param bool   $is_return Whether to return the result set.
     *
     * @return mixed The result set if $is_return is true, otherwise true.
     */
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
