<?php

/**
 * Provides basic security utilities for input sanitization.
 *
 * This class offers simple, static methods designed to help protect against common
 * web vulnerabilities. Its primary focus is on mitigating Cross-Site Scripting (XSS)
 * by cleaning and sanitizing user-provided data before it is rendered on a page.
 */
class Security
{
    /**
     * Sanitizes a string to mitigate Cross-Site Scripting (XSS) attacks.
     *
     * This method applies two main sanitization techniques to the input data:
     * 1. It converts special HTML characters (like `<`, `>`, `"`, `'`) into their
     *    corresponding HTML entities using `htmlspecialchars`. This prevents the browser
     *    from interpreting them as HTML code.
     * 2. It uses a regular expression to strip out any `<script>` tags and their
     *    content from the string, providing an additional layer of protection.
     *
     * @param string $data The raw input string to be sanitized.
     *
     * @return string The sanitized string, which is safer for outputting in an HTML context.
     */
    public static function sanitizeInput($data)
    {
        // Convert special characters to HTML entities to prevent them from being rendered as HTML.
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // Remove any script tags and their content.
        $data = preg_replace('/<script.*?<\/script>/is', '', $data);

        return $data;
    }

    /**
     * A flexible validation and sanitization dispatcher.
     *
     * This method acts as a wrapper that can be extended to support various types
     * of validation and sanitization routines in the future. Currently, it only
     * supports 'xss' sanitization, for which it calls the `sanitizeInput` method.
     *
     * @param string $input The input data to be processed.
     * @param string $type  The type of sanitization to perform. At present, only
     *                      'xss' is implemented.
     *
     * @return string The processed and sanitized input string. If the specified `$type` is
     *                not recognized, the original, unmodified input is returned.
     */
    public static function validateAndSanitize($input, $type)
    {
        if ($type === 'xss') {
            return self::sanitizeInput($input);
        }

        return $input;
    }
}
