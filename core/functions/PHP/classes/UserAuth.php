<?php

/**
 * Defines the path to the JSON file that configures user authentication parameters.
 */
define('JSON_FOLDER', __DIR__.'/../../../../Json/AuthParams.json');

/**
 * Handles user authentication processes like sign-up, sign-in, session management,
 * and dynamic database schema generation.
 *
 * This class uses a JSON configuration file (`Json/AuthParams.json`) to define the
 * structure and validation rules for the `users` table, allowing for flexible and
 * configurable user authentication logic.
 */
class UserAuth
{
    /**
     * Generates a `CREATE TABLE` SQL statement for the `users` table based on the JSON configuration.
     *
     * This method reads the structure and constraints from `Json/AuthParams.json` and
     * dynamically constructs an SQL query to create the `users` table. It maps JSON
     * data types to corresponding SQL types and includes constraints like `NOT NULL`,
     * `UNIQUE`, and `DEFAULT`.
     *
     * @return string The generated SQL `CREATE TABLE` query as a string.
     */
    public static function generateSQL()
    {
        $jsonString = file_get_contents(JSON_FOLDER);
        $data = json_decode($jsonString, true);

        // Check if JSON decoding was successful
        if ($data === null) {
            error_log('Error decoding JSON in generateSQL.');
            exit('Error decoding JSON.');
        }

        // SECURITY: Validate JSON structure
        if (!is_array($data)) {
            error_log('Invalid JSON structure in generateSQL.');
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
            // SECURITY: Validate maxLength is numeric and within bounds
            if (!is_numeric($maxLength) || $maxLength < 1 || $maxLength > 65535) {
                $maxLength = 255;
            }
            if ($sqlType === 'VARCHAR') {
                $sqlType .= "($maxLength)";
            }

            // Required field
            $required = isset($attributes['required']) ? 'NOT NULL' : '';

            // Unique constraint
            $unique = isset($attributes['unique']) ? 'UNIQUE' : '';

            // Default value — use addslashes as minimal escaping (defense in depth; JSON-sourced)
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
     * Authenticates a user and starts a session upon successful sign-in.
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
        // Ensure $details is a non-empty array
        if (!is_array($details) || empty($details)) {
            return false;
        }

        $password = $details['password'] ?? null;
        unset($details['password']);

        // SECURITY: Whitelist allowed column names to prevent SQL injection
        $allowedColumns = array_keys(json_decode(file_get_contents(JSON_FOLDER), true));

        $params = [];
        $conditions = [];

        foreach ($details as $key => $value) {
            // Only allow known column names
            if (!in_array($key, $allowedColumns)) {
                continue;
            }
            $conditions[] = "$key = ?";
            $params[] = $value;
        }

        if (empty($conditions)) {
            return false;
        }

        $placeholders = implode(' AND ', $conditions);
        $sql = "SELECT * FROM users WHERE $placeholders";
        $newUser = executeStatement($sql, $params);

        if (count($newUser) > 0) {
            $user = $newUser[0];
            if ($password !== null && password_verify($password, $user['password'])) {
                // SECURITY: Regenerate session ID to prevent session fixation
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_regenerate_id(true);
                }
                $_SESSION['user_id'] = $user['id'];

                return 'User Found';
            }
        }

        return 'User Not Found';
    }

    /**
     * Validates user details against the rules in the JSON configuration.
     *
     * This helper method performs comprehensive validation on the provided user details based
     * on the rules defined in `Json/AuthParams.json`. It checks types, lengths, formats,
     * and other constraints specified in the configuration.
     *
     * @param array $details An associative array containing the new user's details,
     *                       where keys correspond to the `users` table columns.
     *
     * @return string|null Returns `null` if all validation passes, or an error message string
     *                     describing the first validation failure encountered.
     */
    private static function validateUserDetails($details)
    {
        $data = self::getAuthConfig();
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
            // SECURITY: Validate key is a valid column name
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                return "Invalid parameter: $key";
            }
            if (!isset($data[$key])) {
                return "Invalid parameter: $key";
            }
            $validationError = self::validateFieldRules($key, $value, $data[$key]);
            if ($validationError !== null) {
                return $validationError;
            }
        }

        return null;
    }

    /**
     * Validates a single field against its configuration rules.
     *
     * @param string $key   The field name.
     * @param mixed  $value The field value.
     * @param array  $rules The validation rules for this field.
     *
     * @return string|null Returns `null` if validation passes, or an error message.
     */
    private static function validateFieldRules($key, $value, $rules)
    {
        foreach ($rules as $rule => $constraint) {
            switch ($rule) {
                case 'type':
                    $typeError = self::validateFieldType($key, $value, $constraint);
                    if ($typeError !== null) {
                        return $typeError;
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

        return null;
    }

    /**
     * Validates a field's type against its configured constraint.
     *
     * @param string $key   The field name.
     * @param mixed  $value The field value.
     * @param string $type  The expected type constraint.
     *
     * @return string|null Returns `null` if validation passes, or an error message.
     */
    private static function validateFieldType($key, $value, $type)
    {
        if ($type === 'email' && !Validation::isEmail($value)) {
            return "$key must be a valid email.";
        }
        if ($type === 'number' && !Validation::isNumber($value)) {
            return "$key must be a valid number.";
        }
        if ($type === 'bool' && !Validation::isBool($value)) {
            return "$key must be a valid boolean.";
        }
        if ($type === 'domain' && !Validation::isDomain($value)) {
            return "$key must be a valid domain.";
        }

        return null;
    }

    /**
     * Loads and returns the authentication configuration from the JSON file.
     *
     * @return array|null Returns the configuration array, or `null` on error.
     */
    private static function getAuthConfig()
    {
        $jsonString = file_get_contents(JSON_FOLDER);
        if ($jsonString === false) {
            return null;
        }

        $data = json_decode($jsonString, true);
        if ($data === null || !is_array($data)) {
            return null;
        }

        return $data;
    }

    /**
     * Checks if a user with the given details already exists in the database.
     *
     * @param array $details The user details to check (without password).
     *
     * @return bool True if the user exists, false otherwise.
     */
    private static function userExists($details)
    {
        $allowedColumns = array_keys(json_decode(file_get_contents(JSON_FOLDER), true));
        $safeCheckKeys = array_filter(array_keys($details), fn ($k) => in_array($k, $allowedColumns));
        $placeholders = implode(' AND ', array_map(fn ($k) => "`$k` = ?", $safeCheckKeys));
        $existingUser = executeStatement("SELECT * FROM users WHERE $placeholders", array_values(array_intersect_key($details, array_flip($safeCheckKeys))));

        return !empty($existingUser);
    }

    /**
     * Inserts a new user into the database.
     *
     * @param array $details The user details including password.
     *
     * @return string The result message.
     */
    private static function insertUser($details)
    {
        // Hash password before saving
        if (isset($details['password'])) {
            $details['password'] = password_hash($details['password'], PASSWORD_DEFAULT);
        }

        // SECURITY: Whitelist column names to prevent SQL injection
        $allowedColumns = array_keys(json_decode(file_get_contents(JSON_FOLDER), true));
        $safeKeys = array_filter(array_keys($details), fn ($k) => in_array($k, $allowedColumns));

        // Insert new user
        $columns = implode(', ', array_map(fn ($k) => "`$k`", $safeKeys));
        $placeholders = implode(', ', array_fill(0, count($safeKeys), '?'));
        $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";

        try {
            executeStatement($sql, array_values(array_intersect_key($details, array_flip($safeKeys))));
            $checkDetails = $details;
            unset($checkDetails['password']);
            $safeCheckKeys = array_filter(array_keys($checkDetails), fn ($k) => in_array($k, $allowedColumns));
            $placeholders = implode(' AND ', array_map(fn ($k) => "`$k` = ?", $safeCheckKeys));
            $sql = "SELECT id FROM users WHERE $placeholders";
            $newUser = executeStatement($sql, array_values(array_intersect_key($checkDetails, array_flip($safeCheckKeys))))[0];
            $_SESSION['user_id'] = $newUser['id'];

            return 'User successfully registered.';
        } catch (Exception $e) {
            error_log('Error inserting user: '.$e->getMessage());
            if (getEnvValue('DEV_MODE') === 'true') {
                return 'Error inserting user: '.$e->getMessage();
            }

            return 'Error inserting user.';
        }
    }

    /**
     * Registers a new user after validating their details against the rules in the JSON configuration.
     *
     * This method performs comprehensive validation on the provided user details based
     * on the rules defined in `Json/AuthParams.json`. If validation passes and the
     * user does not already exist, it inserts a new record into the `users` table
     * and starts a new session for the user.
     *
     * @param array $details An associative array containing the new user's details,
     *                       where keys correspond to the `users` table columns.
     *
     * @return string A message indicating the result of the registration attempt,
     *                such as success, a specific validation error, or a database error.
     */
    public static function signUp($details)
    {
        $data = self::getAuthConfig();
        if ($data === null) {
            return 'Error decoding JSON.';
        }

        // Validate user details
        $validationError = self::validateUserDetails($details);
        if ($validationError !== null) {
            return $validationError;
        }

        // Check if user already exists
        $checkDetails = $details;
        unset($checkDetails['password']);
        if (self::userExists($checkDetails)) {
            return 'User already exists.';
        }

        // Insert new user
        return self::insertUser($details);
    }

    /**
     * Checks if a user is currently authenticated by verifying the session.
     *
     * @return bool Returns `true` if a `user_id` is set and not empty in the
     *              current session, otherwise returns `false`.
     */
    public static function checkUser()
    {
        // SECURITY: Use strict comparison to prevent type juggling attacks
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] !== '';
    }

    /**
     * Logs out the currently authenticated user by clearing their session identifier.
     *
     * @return string A confirmation message indicating that the user has been logged out.
     */
    public static function logout()
    {
        // SECURITY: Regenerate session ID to prevent session fixation
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        // SECURITY: Unset all session variables
        $_SESSION = [];
        // SECURITY: Destroy session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        // SECURITY: Destroy session
        session_destroy();
        $_SESSION['user_id'] = '';

        return 'User logged out.';
    }
}
