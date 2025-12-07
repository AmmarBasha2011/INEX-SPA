<?php

/**
 * A class for user authentication and management.
 */
define('JSON_FOLDER', __DIR__.'/../../../../Json/AuthParams.json');
class UserAuth
{
    /**
     * Generates the SQL query to create the users table based on the JSON configuration.
     *
     * @return string The generated SQL query.
     */
    public static function generateSQL()
    {
        $jsonString = file_get_contents(JSON_FOLDER);

        $data = json_decode($jsonString, true);

        if ($data === null) {
            exit('Error decoding JSON.');
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
     * Signs in a user.
     *
     * @param array $details The user's login details.
     *
     * @return string|false The result of the sign-in attempt.
     */
    public static function signIn($details)
    {
        if (!is_array($details) || empty($details)) {
            return false;
        }

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
     * Signs up a new user.
     *
     * @param array $details The user's registration details.
     *
     * @return string The result of the sign-up attempt.
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

        foreach ($data as $key => $value) {
            if (!empty($value['required']) && $value['required'] === true && !isset($details[$key])) {
                return "Missing required parameter: $key";
            }
        }

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
                            return "$key must end with ".implode(', ', $constraint).'.';
                        }
                        break;
                    case 'shouldNotStart':
                        if (Validation::isStartWith($value, $constraint)) {
                            return "$key should not start with ".implode(', ', $constraint).'.';
                        }
                        break;
                    case 'shouldNotEnd':
                        if (Validation::isEndWith($value, $constraint)) {
                            return "$key should not end with ".implode(', ', $constraint).'.';
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

        $placeholders = implode(' AND ', array_map(fn ($k) => "$k = ?", array_keys($details)));
        $existingUser = executeStatement("SELECT * FROM users WHERE $placeholders", array_values($details));
        if (!empty($existingUser)) {
            return 'User already exists.';
        }

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
     * Checks if a user is logged in.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public static function checkUser()
    {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] != '';
    }

    /**
     * Logs out the current user.
     *
     * @return string The result of the logout attempt.
     */
    public static function logout()
    {
        $_SESSION['user_id'] = '';

        return 'User logged out.';
    }
}
