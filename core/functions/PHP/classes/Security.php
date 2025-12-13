<?php
/**
 * Basic Input Sanitization
 *
 * This file contains the Security class, a static utility for performing
 * basic security-related input filtering.
 */

/**
 * A class for handling basic security measures.
 *
 * This class provides simple, static methods for sanitizing user input to
 * prevent common vulnerabilities like Cross-Site Scripting (XSS).
 *
 * @package INEX\Security
 */
class Security
{
    /**
     * Sanitizes a string to protect against XSS attacks.
     *
     * This method performs two main actions:
     * 1. It converts special HTML characters to their entity equivalents using `htmlspecialchars`.
     * 2. It strips out any `<script>` tags and their content.
     *
     * @param string $data The raw input data to be sanitized.
     *
     * @return string The sanitized data, safe for outputting in HTML.
     */
    public static function sanitizeInput($data)
    {
        // Convert special characters to HTML entities to prevent them from being rendered.
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // Remove any script tags to prevent JavaScript execution.
        $data = preg_replace('/<script.*?<\/script>/is', '', $data);

        return $data;
    }

    /**
     * Validates and sanitizes input based on a specified type.
     *
     * This method acts as a router for different sanitization routines. Currently,
     * it only supports 'xss' sanitization, which calls the `sanitizeInput` method.
     *
     * @param string $input The input data to be processed.
     * @param string $type  The type of validation/sanitization to perform (e.g., 'xss').
     *
     * @return string The processed and sanitized input.
     */
    public static function validateAndSanitize($input, $type)
    {
        if ($type === 'xss') {
            return self::sanitizeInput($input);
        }

        // Return the original input if the type is not recognized.
        return $input;
    }
}
