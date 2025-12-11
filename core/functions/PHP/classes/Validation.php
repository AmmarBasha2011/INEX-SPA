<?php

/**
 * A utility class for various data validation checks.
 *
 * This class provides a collection of static methods for common validation
 * tasks such as checking for valid emails, string lengths, and numeric values.
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
     * @param int    $maxLength The maximum allowed length.
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
     * @param int    $minLength The minimum required length.
     *
     * @return bool True if the string length is greater than or equal to the min length, false otherwise.
     */
    public static function isMinTextLength($text, $minLength)
    {
        return strlen($text) >= $minLength;
    }

    /**
     * Checks if a domain is a subdomain.
     *
     * @param string $domain The domain string to check.
     *
     * @return bool True if the domain is a subdomain, false otherwise.
     */
    public static function isSubDomain($domain)
    {
        return substr_count($domain, '.') > 1;
    }

    /**
     * Checks if a URL has a subdirectory path.
     *
     * @param string $domain The URL to check.
     *
     * @return bool True if the URL contains a subdirectory, false otherwise.
     */
    public static function isSubDir($domain)
    {
        return parse_url($domain, PHP_URL_PATH) && trim(parse_url($domain, PHP_URL_PATH), '/') !== '';
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
     *
     * @param string $text    The string to check.
     * @param array  $endList An array of possible ending strings.
     *
     * @return bool True if the string ends with one of the suffixes, false otherwise.
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
     *
     * @param string $text      The string to check.
     * @param array  $startList An array of possible starting strings.
     *
     * @return bool True if the string starts with one of the prefixes, false otherwise.
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
     * @param mixed $text The value to check.
     *
     * @return bool True if the value is numeric, false otherwise.
     */
    public static function isNumber($text)
    {
        return is_numeric($text);
    }

    /**
     * Checks if a value can be interpreted as a boolean.
     *
     * @param mixed $text The value to check.
     *
     * @return bool True if the value represents a boolean, false otherwise.
     */
    public static function isBool($text)
    {
        return $text === true || $text === false || $text === 'true' || $text === 'false' || $text === 1 || $text === 0 || $text === '1' || $text === '0';
    }
}
