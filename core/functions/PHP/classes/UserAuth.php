<?php

/**
 * The path to the JSON file defining the user authentication parameters.
 */
define('JSON_FOLDER', __DIR__.'/../../../../Json/AuthParams.json');

/**
 * A class for handling user authentication, including sign-up, sign-in, and session management.
 *
 * This class dynamically generates the users table schema, validates user input based on
 * a JSON configuration file, and manages the user's logged-in state.
 */
class UserAuth
{
    /**
     * Generates a 'CREATE TABLE' SQL statement for the 'users' table based on a JSON config.
     *
     * @return string The generated SQL 'CREATE TABLE' query.
     */
    public static function generateSQL()
    {
        $jsonString = file_get_contents(JSON_FOLDER);

        $data = json_decode($jsonString, true);

        // Check if JSON decoding was successful
        if ($data === null) {
            exit('Error decoding JSON.');
        }

        // Initialize the SQL query
        $sql = "CREATE TABLE IF NOT EXISTS users (\n";
        $sql .= "  id INT AUTO_INCREMENT PRIMARY KEY,\n"; // Auto-increment ID

        // Mapping JSON data types to SQL types
        $typeMapping = [
            'text'   => 'VARCHAR',
            'email'  => 'VARCHAR',
            'number' => 'INT',
            'bool'   => 'TINYINT(1)',
            'domain' => 'VARCHAR',
        ];

        foreach ($data as $field => $attributes) {
            $type = $attributes['type'];
            $sqlType = $typeMapping[$type] ?? 'TEXT'; // Default to TEXT if type not found

            // Handle VARCHAR length
            $maxLength = $attributes['maxLength'] ?? 255;
            if ($sqlType === 'VARCHAR') {
                $sqlType .= "($maxLength)";
            }

            // Required field
            $required = isset($attributes['required']) ? 'NOT NULL' : '';

            // Unique constraint
            $unique = isset($attributes['unique']) ? 'UNIQUE' : '';

            // Default value
            $default = isset($attributes['default']) ? "DEFAULT '".addslashes($attributes['default'])."'" : '';

            // Construct column definition
            $sql .= "  `$field` $sqlType $required $unique $default,\n";
        }

        // Remove last comma and add closing bracket
        $sql .= "  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n);";

        // Print generated SQL
        return $sql;
    }

    /**
     * Signs in a user based on the provided details.
     *
     * @param array $details An associative array where keys are column names and values are the user's credentials.
     *
     * @return string|false 'User Found' on success, 'User Not Found' on failure, or false if input is invalid.
     */
    public static function signIn($details)
    {
        // Ensure $details is a non-empty array
        if (!is_array($details) || empty($details)) {
            return false;
        }

        // Extract values dynamically
        $params = [];
        $conditions = [];

        foreach ($details as $key => $value) {
            $conditions[] = "$key = ?";
            $params[] = $value;
        }

        $placeholders = implode(' AND ', array_map(fn ($key) => "$key = ?", array_keys($details)));
        $sql = "SELECT * FROM users WHERE $placeholders";
        $newUser = executeStatement($sql, array_values($details));
        if (count($newUser) > 0) {
            $_SESSION['user_id'] = $newUser[0]['id'];

            return 'User Found';
        } else {
            return 'User Not Found';
        }
    }

    /**
     * Registers a new user after validating their details against the JSON configuration.
     *
     * @param array $details An associative array of the new user's details.
     *
     * @return string A message indicating the result of the registration attempt.
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

        // Validate required fields
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

        // Check if user already exists
        $placeholders = implode(' AND ', array_map(fn ($k) => "$k = ?", array_keys($details)));
        $existingUser = executeStatement("SELECT * FROM users WHERE $placeholders", array_values($details));
        if (!empty($existingUser)) {
            return 'User already exists.';
        }

        // Insert new user
        $columns = implode(', ', array_keys($details));
        $placeholders = implode(', ', array_fill(0, count($details), '?'));
        $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";

        try {
            executeStatement($sql, array_values($details));
            $placeholders = implode(' AND ', array_map(fn ($key) => "$key = ?", array_keys($details)));
            $sql = "SELECT id FROM users WHERE $placeholders";
            $newUser = executeStatement($sql, array_values($details))[0];
            $_SESSION['user_id'] = $newUser['id'];

            return 'User successfully registered.';
        } catch (Exception $e) {
            return 'Error inserting user: '.$e->getMessage();
        }
    }

    /**
     * Checks if a user is currently logged in.
     *
     * @return bool True if a user is logged in, false otherwise.
     */
    public static function checkUser()
    {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] != '';
    }

    /**
     * Logs out the current user.
     *
     * @return string A confirmation message.
     */
    public static function logout()
    {
        $_SESSION['user_id'] = '';

        return 'User logged out.';
    }
}
