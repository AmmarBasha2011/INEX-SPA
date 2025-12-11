<?php

/**
 * A wrapper class for PDO to simplify database interactions.
 *
 * This class handles the database connection using credentials from the .env file
 * and provides a simple method for executing prepared SQL queries.
 */
class Database
{
    /**
     * The PDO instance.
     * @var PDO
     */
    private $pdo;

    /**
     * Establishes a database connection.
     *
     * The constructor retrieves database credentials from the .env file,
     * creates a new PDO instance, and sets it up with default options.
     * The script will terminate on a connection failure.
     *
     * @param string $charset The character set to use for the connection. Defaults to 'utf8mb4'.
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
     * Prepares and executes an SQL query.
     *
     * @param string $sql The SQL query to execute. Can contain placeholders (e.g., ?).
     * @param array $params An array of parameters to bind to the placeholders in the SQL query.
     * @param bool $is_return If true, returns the result set as an array of associative arrays.
     *                        If false, returns true on success.
     * @return array|bool The result set or true, depending on the value of $is_return.
     */
    public function query($sql, $params = [], $is_return = true)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $is_return ? $stmt->fetchAll() : true;
    }
}
