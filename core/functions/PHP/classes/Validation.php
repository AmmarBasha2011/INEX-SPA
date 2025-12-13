<?php
/**
 * Data Validation Utility
 *
 * This file provides the Validation class, a static utility for performing
 * common data validation checks such as email format, string length, and numeric types.
 */

/**
 * A utility class for various data validation checks.
 *
 * This class provides a collection of static methods for common validation
 * tasks. All methods are static and return a boolean result.
 *
 * @package INEX\Validation
 */
class Validation
{
    /**
     * Validates if a string is a well-formed email address.
     *
     * @param string $text The string to validate.
     *
     * @return bool True if the string is a valid email, false otherwise.
     */
    public static function isEmail($text)
    {
        return filter_var($text, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Checks if a string's length is within a maximum limit.
     *
     * @param string $text      The string to check.
     * @param int    $maxLength The maximum allowed length (inclusive).
     *
     * @return bool True if the string length is less than or equal to the max length, false otherwise.
     */
    public static function isTextLength($text, $maxLength)
    {
        return strlen($text) <= $maxLength;
    }

    /**
     * Checks if a string's length meets a minimum requirement.
     *
     * @param string $text      The string to check.
     * @param int    $minLength The minimum required length (inclusive).
     *
     * @return bool True if the string length is greater than or equal to the min length, false otherwise.
     */
    public static function isMinTextLength($text, $minLength)
    {
        return strlen($text) >= $minLength;
    }

    /**
     * Checks if a domain string appears to be a subdomain.
     * This is a simple check based on the number of dots.
     *
     * @param string $domain The domain string to check.
     *
     * @return bool True if the domain contains more than one dot, false otherwise.
     */
    public static function isSubDomain($domain)
    {
        return substr_count($domain, '.') > 1;
    }

    /**
     * Checks if a URL string contains a subdirectory path.
     *
     * @param string $domain The URL to check.
     *
     * @return bool True if the URL's path component is not empty, false otherwise.
     */
    public static function isSubDir($domain)
    {
        $path = parse_url($domain, PHP_URL_PATH);
        return $path && trim($path, '/') !== '';
    }

    /**
     * Validates if a string is a well-formed domain name.
     *
     * @param string $text The string to validate.
     *
     * @return bool True if the string is a valid domain, false otherwise.
     */
    public static function isDomain($text)
    {
        return (bool) filter_var($text, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

    /**
     * Checks if a string ends with any of the specified suffixes.
     * The comparison is case-sensitive.
     *
     * @param string $text    The string to check.
     * @param array  $endList An array of possible ending strings.
     *
     * @return bool True if the string ends with one of the provided suffixes, false otherwise.
     */
    public static function isEndWith($text, $endList)
    {
        foreach ($endList as $end) {
            if (str_ends_with($text, $end)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if a string starts with any of the specified prefixes.
     * The comparison is case-sensitive.
     *
     * @param string $text      The string to check.
     * @param array  $startList An array of possible starting strings.
     *
     * @return bool True if the string starts with one of the provided prefixes, false otherwise.
     */
    public static function isStartWith($text, $startList)
    {
        foreach ($startList as $start) {
            if (str_starts_with($text, $start)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if a value is numeric.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if the value is numeric, false otherwise.
     */
    public static function isNumber($value)
    {
        return is_numeric($value);
    }

    /**
     * Checks if a value can be interpreted as a boolean.
     *
     * Accepts true, false, 'true', 'false', 1, 0, '1', and '0'.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if the value represents a boolean, false otherwise.
     */
    public static function isBool($value)
    {
        return in_array($value, [true, false, 'true', 'false', 1, 0, '1', '0'], true);
    }
}
