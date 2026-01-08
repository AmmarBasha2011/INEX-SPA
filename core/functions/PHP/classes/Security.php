<?php

/**
 * Provides basic security utilities for input sanitization.
 *
 * This class offers simple, static methods to help protect against common web
 * vulnerabilities, primarily focusing on Cross-Site Scripting (XSS) by cleaning
 * user-provided data before it is rendered on a page.
 */
class Security
{
    /**
     * Sanitizes a string to mitigate Cross-Site Scripting (XSS) attacks.
     *
     * This method is a crucial tool for preventing XSS. It applies two main
     * sanitization techniques:
     * 1. It converts special HTML characters (like `<`, `>`, `"`, `'`) into their
     *    corresponding HTML entities using `htmlspecialchars`. This prevents the browser
     *    from interpreting them as HTML tags.
     * 2. It uses a regular expression to strip out any `<script>` tags and their
     *    content entirely from the string, providing an additional layer of defense.
     *
     * @param string $data The raw input string to be sanitized.
     *
     * @return string The sanitized string, which is safer for outputting in an HTML context.
     */
    public static function sanitizeInput($data)
    {
        // Convert special characters to HTML entities to prevent tag injection.
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // Remove any script tags and their content.
        $data = preg_replace('/<script.*?<\/script>/is', '', $data);

        return $data;
    }

    /**
     * A flexible validation and sanitization dispatcher.
     *
     * This method acts as a wrapper that can be extended to support various types
     * of validation and sanitization routines. Currently, its primary function is
     * to provide 'xss' sanitization, for which it calls the `sanitizeInput` method.
     * This structure allows for easy addition of other sanitization types in the future.
     *
     * @param string $input The input data to be processed.
     * @param string $type  The type of sanitization to perform. Currently, only
     *                      'xss' is implemented.
     *
     * @return string The processed and sanitized input string. If the sanitization type
     *                is not recognized, the original input is returned unmodified.
     */
    public static function validateAndSanitize($input, $type)
    {
        if ($type === 'xss') {
            return self::sanitizeInput($input);
        }

        return $input;
    }
}
