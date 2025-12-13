<?php
/**
 * User Authentication and Management
 *
 * This file contains the UserAuth class, which provides a comprehensive set of
 * static methods for user registration, login, session management, and database
 * schema generation based on a JSON configuration.
 */

/**
 * The path to the JSON file defining the user authentication parameters.
 * This constant makes it easier to reference the configuration file.
 */
define('JSON_FOLDER', __DIR__.'/../../../../Json/AuthParams.json');

/**
 * A class for handling user authentication, including sign-up, sign-in, and session management.
 *
 * This class dynamically generates the users table schema based on a JSON configuration,
 * validates user input against the defined rules, and manages the user's logged-in
 * state through the session. All methods are static.
 *
 * @package INEX\Authentication
 */
class UserAuth
{
    /**
     * Generates a 'CREATE TABLE' SQL statement for the 'users' table based on a JSON config.
     *
     * This method reads the structure and constraints for the users table from
     * `Json/AuthParams.json` and dynamically constructs a corresponding SQL query.
     *
     * @return string The generated SQL 'CREATE TABLE' query.
     */
    public static function generateSQL()
    {
        $jsonString = file_get_contents(JSON_FOLDER);
        $data = json_decode($jsonString, true);

        if ($data === null) {
            exit('Error decoding JSON for SQL generation.');
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

            $required = isset($attributes['required']) ? 'NOT NULL' : '';
            $unique = isset($attributes['unique']) ? 'UNIQUE' : '';
            $default = isset($attributes['default']) ? "DEFAULT '".addslashes($attributes['default'])."'" : '';

            $sql .= "  `$field` $sqlType $required $unique $default,\n";
        }

        $sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n);";

        return $sql;
    }

    /**
     * Signs in a user based on the provided credentials.
     *
     * @param array $details An associative array where keys are column names (e.g., 'username', 'password')
     *                       and values are the credentials to be matched.
     *
     * @return string|false 'User Found' on successful login, 'User Not Found' on failure,
     *                      or false if the input details are invalid.
     */
    public static function signIn($details)
    {
        if (!is_array($details) || empty($details)) {
            return false;
        }

        $placeholders = implode(' AND ', array_map(fn ($key) => "$key = ?", array_keys($details)));
        $sql = "SELECT * FROM users WHERE $placeholders";
        $user = executeStatement($sql, array_values($details));

        if (count($user) > 0) {
            $_SESSION['user_id'] = $user[0]['id'];
            return 'User Found';
        } else {
            return 'User Not Found';
        }
    }

    /**
     * Registers a new user after validating their details against the JSON configuration.
     *
     * This method performs a comprehensive validation of the provided details against
     * the rules defined in `Json/AuthParams.json` before attempting to create a new user.
     *
     * @param array $details An associative array of the new user's details to be validated and stored.
     *
     * @return string A message indicating the success or failure of the registration attempt.
     */
    public static function signUp($details)
    {
        $jsonString = file_get_contents(JSON_FOLDER);
        if ($jsonString === false) {
            return 'Error reading JSON file.';
        }

        $data = json_decode($jsonString, true);
        if ($data === null) {
            return 'Error decoding JSON.';
        }

        // Validate that all required fields are present.
        foreach ($data as $key => $value) {
            if (!empty($value['required']) && $value['required'] === 'true' && (empty($details[$key]) || !isset($details[$key]))) {
                return "Missing required parameter: $key";
            }
        }

        // Validate each provided detail against its rules in the JSON config.
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
                        if (!Validation::isEndWith($value, $constraint)) {
                            return "$key must end with $constraint.";
                        }
                        break;
                    case 'shouldNotStart':
                        if (Validation::isStartWith($value, $constraint)) {
                            return "$key should not start with $constraint.";
                        }
                        break;
                    case 'shouldNotEnd':
                        if (Validation::isEndWith($value, $constraint)) {
                            return "$key should not end with $constraint.";
                        }
                        break;
                    case 'notEqual':
                        if (in_array($value, (array) $constraint)) {
                            return "$key contains a forbidden value.";
                        }
                        break;
                    case 'shouldStart':
                        if (!Validation::isStartWith($value, $constraint)) {
                            return "$key must start with $constraint.";
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

        // Check if a user with the same details already exists.
        $placeholders = implode(' AND ', array_map(fn ($k) => "$k = ?", array_keys($details)));
        $existingUser = executeStatement("SELECT * FROM users WHERE $placeholders", array_values($details));
        if (!empty($existingUser)) {
            return 'User already exists.';
        }

        // Insert the new user into the database.
        $columns = implode(', ', array_keys($details));
        $valuePlaceholders = implode(', ', array_fill(0, count($details), '?'));
        $sql = "INSERT INTO users ($columns) VALUES ($valuePlaceholders)";

        try {
            executeStatement($sql, array_values($details));
            // After successful insertion, log the new user in.
            $sql = "SELECT id FROM users WHERE $placeholders";
            $newUser = executeStatement($sql, array_values($details))[0];
            $_SESSION['user_id'] = $newUser['id'];
            return 'User successfully registered.';
        } catch (Exception $e) {
            return 'Error inserting user: '.$e->getMessage();
        }
    }

    /**
     * Checks if a user is currently logged in by verifying the session.
     *
     * @return bool True if a user is logged in (i.e., `$_SESSION['user_id']` is set and not empty), false otherwise.
     */
    public static function checkUser()
    {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] != '';
    }

    /**
     * Logs out the current user by clearing their user ID from the session.
     *
     * @return string A confirmation message indicating the user has been logged out.
     */
    public static function logout()
    {
        $_SESSION['user_id'] = '';
        return 'User logged out.';
    }
}
