<?php

/**
 * Defines the path to the JSON file that configures user authentication parameters.
 * This constant is used throughout the UserAuth class to locate its configuration.
 */
define('JSON_FOLDER', __DIR__.'/../../../../Json/AuthParams.json');

/**
 * Handles user authentication, registration, session management, and database schema generation.
 *
 * This class uses a dynamic approach, relying on a JSON configuration file
 * (`Json/AuthParams.json`) to define the structure, validation rules, and constraints
 * for the `users` table. This allows for flexible and easily configurable user
 * authentication logic without modifying the class code.
 */
class UserAuth
{
    /**
     * Generates a `CREATE TABLE` SQL statement for the `users` table based on the JSON configuration.
     *
     * This method reads the structure and constraints from `Json/AuthParams.json` and
     * dynamically constructs an SQL query to create the `users` table. It maps JSON
     * data types (e.g., 'text', 'email', 'number') to corresponding SQL data types
     * (e.g., VARCHAR, INT) and includes constraints like `NOT NULL`, `UNIQUE`, and `DEFAULT`.
     * An `id` primary key and a `created_at` timestamp are automatically included.
     *
     * @return string The generated SQL `CREATE TABLE` query as a string.
     */
    public static function generateSQL()
    {
        $jsonString = file_get_contents(JSON_FOLDER);
        $data = json_decode($jsonString, true);

        if ($data === null) {
            exit('Error decoding JSON configuration for UserAuth.');
        }

        $sql = "CREATE TABLE IF NOT EXISTS users (\n";
        $sql .= "  id INT AUTO_INCREMENT PRIMARY KEY,\n";

        $typeMapping = [
            'text'   => 'VARCHAR',
            'email'  => 'VARCHAR',
            'number' => 'INT',
            'bool'   => 'TINYINT(1)',
            'domain' => 'VARCHAR',
        ];

        foreach ($data as $field => $attributes) {
            $type = $attributes['type'];
            $sqlType = $typeMapping[$type] ?? 'TEXT';

            $maxLength = $attributes['maxLength'] ?? 255;
            if ($sqlType === 'VARCHAR') {
                $sqlType .= "($maxLength)";
            }

            $required = isset($attributes['required']) && $attributes['required'] ? 'NOT NULL' : '';
            $unique = isset($attributes['unique']) && $attributes['unique'] ? 'UNIQUE' : '';
            $default = isset($attributes['default']) ? "DEFAULT '".addslashes($attributes['default'])."'" : '';

            $sql .= "  `$field` $sqlType $required $unique $default,\n";
        }

        $sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n);";

        return $sql;
    }

    /**
     * Authenticates a user and starts a session upon successful sign-in.
     *
     * This method queries the `users` table to find a record that matches all provided
     * credentials. If a match is found, the user's ID is stored in the session.
     *
     * @param array $details An associative array where keys are database column names
     *                       (e.g., 'username', 'password') and values are the credentials
     *                       to be verified.
     *
     * @return string|false Returns 'User Found' on successful authentication, 'User Not Found'
     *                      if the credentials do not match any user, or `false` if the
     *                      input details are invalid or empty.
     */
    public static function signIn($details)
    {
        if (!is_array($details) || empty($details)) {
            return false;
        }

        $placeholders = implode(' AND ', array_map(fn ($key) => "`$key` = ?", array_keys($details)));
        $sql = "SELECT * FROM users WHERE $placeholders";
        $user = executeStatement($sql, array_values($details));

        if (!empty($user) && count($user) > 0) {
            $_SESSION['user_id'] = $user[0]['id'];

            return 'User Found';
        } else {
            return 'User Not Found';
        }
    }

    /**
     * Registers a new user after validating their details against the rules in the JSON configuration.
     *
     * This method performs comprehensive validation on the provided user details based
     * on the rules defined in `Json/AuthParams.json`. If all validations pass and the
     * user does not already exist, it inserts a new record into the `users` table
     * and starts a new session for that user by storing their new ID.
     *
     * @param array $details An associative array containing the new user's details,
     *                       where keys correspond to the `users` table columns.
     *
     * @return string A message indicating the result of the registration attempt, such as success,
     *                a specific validation error, or a database error.
     */
    public static function signUp($details)
    {
        $jsonString = file_get_contents(JSON_FOLDER);
        if ($jsonString === false) {
            return 'Error reading JSON configuration file.';
        }

        $data = json_decode($jsonString, true);
        if ($data === null) {
            return 'Error decoding JSON configuration.';
        }

        // --- Validation Phase ---
        // (Implementation details are omitted for brevity but include checks for required fields, types, lengths, etc.)
        foreach ($data as $key => $value) {
            if (!empty($value['required']) && $value['required'] === 'true' && (empty($details[$key]) || !isset($details[$key]))) {
                return "Missing required parameter: $key";
            }
        }

        // Validate fields based on JSON rules
        foreach ($details as $key => $value) {
            if (!isset($data[$key])) {
                return "Invalid parameter: $key";
            }
            foreach ($data[$key] as $rule => $constraint) {
                switch ($rule) {
                    case 'type':
                        if ($constraint == 'email' && !Validation::isEmail($value)) {
                            return "$key must be a valid email.";
                        }
                        if ($constraint == 'number' && !Validation::isNumber($value)) {
                            return "$key must be a valid number.";
                        }
                        if ($constraint == 'bool' && !Validation::isBool($value)) {
                            return "$key must be a boolean.";
                        }
                        if ($constraint == 'domain' && !Validation::isDomain($value)) {
                            return "$key must be a valid domain.";
                        }
                        break;
                    case 'maxLength':
                        if (!Validation::isTextLength($value, $constraint)) {
                            return "$key exceeds max length of $constraint.";
                        }
                        break;
                    case 'minLength':
                        if (!Validation::isMinTextLength($value, $constraint)) {
                            return "$key must be at least $constraint characters long.";
                        }
                        break;
                    case 'shouldEnd':
                        if (!Validation::isEndWith($value, (array) $constraint)) {
                            $constraintStr = is_array($constraint) ? implode(', ', $constraint) : $constraint;

                            return "$key must end with $constraintStr.";
                        }
                        break;
                    case 'shouldNotStart':
                        if (Validation::isStartWith($value, (array) $constraint)) {
                            $constraintStr = is_array($constraint) ? implode(', ', $constraint) : $constraint;

                            return "$key should not start with $constraintStr.";
                        }
                        break;
                    case 'shouldNotEnd':
                        if (Validation::isEndWith($value, (array) $constraint)) {
                            $constraintStr = is_array($constraint) ? implode(', ', $constraint) : $constraint;

                            return "$key should not end with $constraintStr.";
                        }
                        break;
                    case 'notEqual':
                        if (in_array($value, (array) $constraint)) {
                            return "$key contains a forbidden value.";
                        }
                        break;
                    case 'shouldStart':
                        if (!Validation::isStartWith($value, (array) $constraint)) {
                            $constraintStr = is_array($constraint) ? implode(', ', $constraint) : $constraint;

                            return "$key must start with $constraintStr.";
                        }
                        break;
                    case 'min':
                        if ($value < $constraint) {
                            return "$key must be at least $constraint.";
                        }
                        break;
                    case 'max':
                        if ($value > $constraint) {
                            return "$key must not exceed $constraint.";
                        }
                        break;
                    case 'subDomain':
                        if (!Validation::isSubDomain($value)) {
                            return "$key must be a valid subdomain.";
                        }
                        break;
                    case 'subDir':
                        if (!Validation::isSubDir($value)) {
                            return "$key must be a valid subdirectory.";
                        }
                        break;
                    case 'equal':
                        if (!in_array($value, (array) $constraint)) {
                            return "$key must match one of the allowed values.";
                        }
                        break;
                }
            }
        }

        // --- Existence Check ---
        $placeholders = implode(' AND ', array_map(fn ($k) => "`$k` = ?", array_keys($details)));
        $existingUser = executeStatement("SELECT id FROM users WHERE $placeholders", array_values($details));
        if (!empty($existingUser)) {
            return 'User already exists.';
        }

        // --- Insertion Phase ---
        $columns = '`'.implode('`, `', array_keys($details)).'`';
        $placeholders = implode(', ', array_fill(0, count($details), '?'));
        $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";

        try {
            executeStatement($sql, array_values($details), false); // is_return is false for INSERT

            // Fetch the new user's ID to start the session
            $idPlaceholders = implode(' AND ', array_map(fn ($key) => "`$key` = ?", array_keys($details)));
            $idSql = "SELECT id FROM users WHERE $idPlaceholders";
            $newUser = executeStatement($idSql, array_values($details));

            if (!empty($newUser)) {
                $_SESSION['user_id'] = $newUser[0]['id'];

                return 'User successfully registered.';
            } else {
                return 'User registered, but failed to start session.';
            }
        } catch (Exception $e) {
            return 'Error inserting user: '.$e->getMessage();
        }
    }

    /**
     * Checks if a user is currently authenticated by verifying the session.
     *
     * @return bool Returns `true` if a `user_id` is set and not empty in the
     *              current session, indicating an active session. Otherwise, returns `false`.
     */
    public static function checkUser()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Logs out the currently authenticated user by clearing their session identifier.
     *
     * @return string A confirmation message indicating that the user has been logged out.
     */
    public static function logout()
    {
        // Unset or clear the user_id from the session
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }

        return 'User logged out.';
    }
}
