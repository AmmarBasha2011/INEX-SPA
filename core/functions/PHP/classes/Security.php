<?php

/**
 * A simple security class for sanitizing input.
 */
class Security
{
    /**
     * Sanitizes input to protect against XSS attacks.
     *
     * @param string $data The input data to sanitize.
     *
     * @return string The sanitized data.
     */
    public static function sanitizeInput($data)
    {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        $data = preg_replace('/<script.*?<\/script>/is', '', $data);

        return $data;
    }

    /**
     * Validates and sanitizes input based on the specified type.
     *
     * @param string $input The input data to validate and sanitize.
     * @param string $type  The type of validation and sanitization to perform.
     *
     * @return string The validated and sanitized data.
     */
    public static function validateAndSanitize($input, $type)
    {
        if ($type === 'xss') {
            return self::sanitizeInput($input);
        }

        return $input;
    }
}
