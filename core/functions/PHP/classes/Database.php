<?php

/**
 * Database Connection and Query Handler.
 *
 * This file contains the Database class, a PDO wrapper designed to simplify
 * database connections and query execution for the application.
 */

/**
 * A wrapper class for PDO to simplify database interactions.
 *
 * This class handles the database connection using credentials from the .env file
 * and provides a simple method for executing prepared SQL queries. An instance
 * of this class represents a single connection to the database.
 */
class Database
{
    /**
     * The active PDO connection instance.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Establishes a database connection upon instantiation.
     *
     * The constructor retrieves database credentials (host, name, user, password)
     * from the .env file, creates a new PDO instance, and configures it with
     * standard options for error handling and fetch mode. The script will
     * terminate with an error message on a connection failure.
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
            // In a production environment, this should be handled by a proper error logging
            // and reporting system instead of exiting directly.
            exit('Database connection failed: '.$e->getMessage());
        }
    }

    /**
     * Prepares and executes a parameterized SQL query.
     *
     * This method prepares an SQL statement and binds the provided parameters,
     * protecting against SQL injection attacks. It can be used for both fetching
     * data and executing action queries (INSERT, UPDATE, DELETE).
     *
     * @param string $sql       The SQL query to execute. Use `?` as placeholders for parameters.
     * @param array  $params    An array of values to bind to the placeholders in the SQL query.
     *                          The order of values should match the order of placeholders.
     * @param bool   $is_return Determines the return type.
     *                          - `true` (default): For `SELECT` queries. Returns the result set as an array of associative arrays.
     *                          - `false`: For `INSERT`, `UPDATE`, `DELETE`, etc. Returns `true` on successful execution.
     *
     * @return array|bool If `$is_return` is true, returns an array of rows. If `$is_return` is false, returns `true`.
     */
    public function query($sql, $params = [], $is_return = true)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $is_return ? $stmt->fetchAll() : true;
    }
}
