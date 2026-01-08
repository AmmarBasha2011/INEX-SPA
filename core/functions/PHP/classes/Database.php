<?php

/**
 * A PDO wrapper class for simplifying database connections and queries.
 *
 * This class provides a convenient way to connect to a MySQL database using
 * credentials stored in the .env file. It encapsulates a PDO instance and offers
 * a streamlined method for executing prepared SQL statements, promoting secure
 * database practices by default.
 */
class Database
{
    /**
     * Holds the active PDO database connection instance.
     * This property is initialized by the constructor.
     *
     * @var PDO|null
     */
    private $pdo;

    /**
     * Initializes the database connection upon object creation.
     *
     * The constructor reads the database host, name, user, and password from the
     * application's environment variables (`.env` file). It then establishes a PDO
     * connection with a specified character set. Key PDO attributes are set for
     * robust error handling (`ERRMODE_EXCEPTION`) and efficient fetching (`FETCH_ASSOC`).
     * If the connection fails, the script will terminate with an error message to
     * prevent further execution with a faulty database state.
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
     * Prepares and executes an SQL statement with optional parameter binding.
     *
     * This is the primary method for interacting with the database. It handles both
     * queries that return data (e.g., SELECT) and those that do not (e.g., INSERT,
     * UPDATE, DELETE). By using prepared statements, it helps prevent SQL injection
     * vulnerabilities.
     *
     * @param string $sql       The SQL query to execute. This can contain positional
     *                          placeholders (`?`) for parameter binding.
     * @param array  $params    (Optional) An array of values to bind to the placeholders
     *                          in the SQL query. The order of values must match the
     *                          order of the placeholders.
     * @param bool   $is_return (Optional) If `true`, the method will fetch and return all rows from
     *                          the result set as an array of associative arrays.
     *                          If `false`, it is suitable for non-query statements and
     *                          will return `true` on successful execution.
     *
     * @return array|bool If `$is_return` is `true`, returns an array of results. The array
     *                    will be empty if the query returned no rows.
     *                    If `$is_return` is `false`, returns `true` on successful execution.
     */
    public function query($sql, $params = [], $is_return = true)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $is_return ? $stmt->fetchAll() : true;
    }
}
