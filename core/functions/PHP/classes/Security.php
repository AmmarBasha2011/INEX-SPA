<?php

/**
 * Provides basic security utilities for input sanitization.
 *
 * This class offers simple, static methods to help protect against common web
 * vulnerabilities, primarily focusing on Cross-Site Scripting (XSS) by
 * cleaning user-provided data.
 */
class Security
{
    /**
     * Sanitizes a string to mitigate Cross-Site Scripting (XSS) attacks.
     *
     * This method applies two main sanitization techniques:
     * 1. It converts special HTML characters (like `<`, `>`, `"`) into their
     *    corresponding HTML entities.
     * 2. It strips out any `<script>` tags and their content from the string.
     *
     * @param string $data The raw input string to be sanitized.
     *
     * @return string The sanitized string, safe for outputting in an HTML context.
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
     * A flexible validation and sanitization dispatcher.
     *
     * This method acts as a wrapper that can be extended to support various types
     * of validation and sanitization. Currently, it only supports 'xss' sanitization,
     * for which it calls the `sanitizeInput` method.
     *
     * @param string $input The input data to be processed.
     * @param string $type  The type of sanitization to perform. Currently, only
     *                      'xss' is implemented.
     *
     * @return string The processed and sanitized input string. If the type is
     *                not recognized, the original input is returned.
     */
    public static function validateAndSanitize($input, $type)
    {
        if ($type === 'xss') {
            return self::sanitizeInput($input);
        }

        return $input;
    }
}
