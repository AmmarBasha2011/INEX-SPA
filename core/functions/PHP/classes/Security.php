<?php

/**
 * A class for handling basic security measures.
 *
 * This class provides simple methods for sanitizing user input to prevent
 * common vulnerabilities like Cross-Site Scripting (XSS).
 */
class Security
{
    /**
     * Sanitizes input to protect against XSS attacks.
     *
     * This method uses `htmlspecialchars` to escape HTML entities and also
     * removes any `<script>` tags from the input string.
     *
     * @param string $data The input data to be sanitized.
     *
     * @return string The sanitized data.
     */
    public static function sanitizeInput($data)
    {
        // Remove any unwanted HTML tags
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // Remove any scripts or JavaScript code
        $data = preg_replace('/<script.*?<\/script>/is', '', $data);

        return $data;
    }

    /**
     * Validates and sanitizes input based on the specified type.
     *
     * Currently, this function only supports 'xss' sanitization.
     *
     * @param string $input The input data to be processed.
     * @param string $type  The type of validation/sanitization to perform.
     *
     * @return string The processed input.
     */
    public static function validateAndSanitize($input, $type)
    {
        if ($type === 'xss') {
            return self::sanitizeInput($input);
        }

        return $input;
    }
}
