<?php

/**
 * A PDO wrapper class for simplifying database connections and queries.
 *
 * This class provides a convenient way to connect to a MySQL database using
 * credentials stored in the .env file. It encapsulates a PDO instance and offers
 * a streamlined method for executing prepared SQL statements.
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
     * The constructor reads the database host, name, user, and password from
     * environment variables, then establishes a PDO connection. It sets default
     * error handling and fetch modes for all subsequent queries. If the connection
     * fails, the script will terminate with an error message.
     *
     * @param string $charset The character set for the database connection.
     *                        Defaults to 'utf8mb4'.
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
     * Prepares and executes an SQL statement with optional parameters.
     *
     * This method can be used for both queries that return data (e.g., SELECT)
     * and those that do not (e.g., INSERT, UPDATE, DELETE).
     *
     * @param string $sql       The SQL query to execute. This can contain positional
     *                          placeholders (`?`) for parameter binding.
     * @param array  $params    An array of values to bind to the placeholders in the SQL query.
     * @param bool   $is_return If `true`, the method will fetch and return all rows from
     *                          the result set as an array of associative arrays.
     *                          If `false`, it will return `true` on successful execution.
     *
     * @return array|bool If `$is_return` is `true`, returns an array of results.
     *                    If `$is_return` is `false`, returns `true` on success.
     */
    public function query($sql, $params = [], $is_return = true)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $is_return ? $stmt->fetchAll() : true;
    }
}
