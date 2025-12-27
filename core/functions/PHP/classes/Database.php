<?php

/**
 * A PDO wrapper class for simplifying database connections and executing queries.
 *
 * This class provides a convenient way to connect to a MySQL database using
 * credentials stored in the .env file. It encapsulates a PDO instance, establishes a
 * robust connection, and offers a streamlined method for executing prepared SQL
 * statements, helping to prevent SQL injection vulnerabilities.
 */
class Database
{
    /**
     * Holds the active PDO database connection instance.
     *
     * @var PDO|null This property is initialized in the constructor.
     */
    private $pdo;

    /**
     * Initializes the database connection upon object creation.
     *
     * The constructor reads the database host, name, user, and password from the application's
     * environment variables (`.env` file). It then establishes a PDO connection with
     * recommended settings for error handling (exceptions), fetch mode (associative array),
     * and disabled emulation of prepared statements for enhanced security. If the connection
     * fails, the script will terminate with a descriptive error message.
     *
     * @param string $charset The character set for the database connection.
     *                        Defaults to 'utf8mb4' to support a wide range of characters.
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
     * This versatile method can be used for both queries that return data (e.g., SELECT)
     * and those that modify data (e.g., INSERT, UPDATE, DELETE). It uses prepared
     * statements to safely bind parameters.
     *
     * @param string $sql       The SQL query to execute. This can contain positional
     *                          placeholders (`?`) for parameter binding.
     * @param array  $params    An array of values to bind to the placeholders in the SQL query.
     *                          The order of values should match the order of placeholders.
     * @param bool   $is_return If `true` (default), the method will fetch and return all rows from
     *                          the result set. If `false`, it's suitable for non-query statements
     *                          and will return `true` on successful execution.
     *
     * @return array|bool If `$is_return` is `true`, returns an array of associative arrays
     *                    representing the fetched rows. An empty array is returned if no
     *                    rows are found. If `$is_return` is `false`, returns `true` on success.
     */
    public function query($sql, $params = [], $is_return = true)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $is_return ? $stmt->fetchAll() : true;
    }
}
