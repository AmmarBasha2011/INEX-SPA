<?php

/**
 * Provides a collection of static methods for common data validation tasks.
 *
 * This utility class includes a suite of methods for validating various data formats
 * such as email addresses, string lengths, domains, numeric types, and more. These
 * helpers can be used throughout the application to ensure data integrity.
 */
class Validation
{
    /**
     * Validates whether a given string is a well-formed email address.
     *
     * @param string $text The string to be validated as an email.
     *
     * @return bool Returns `true` if the string is a valid email address according to
     *              `filter_var`, otherwise returns `false`.
     */
    public static function isEmail($text)
    {
        return filter_var($text, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Checks if a string's length does not exceed a specified maximum length.
     *
     * @param string $text      The string to be checked.
     * @param int    $maxLength The maximum number of characters allowed.
     *
     * @return bool Returns `true` if the string's length is less than or equal to `$maxLength`,
     *              otherwise returns `false`.
     */
    public static function isTextLength($text, $maxLength)
    {
        return strlen($text) <= $maxLength;
    }

    /**
     * Checks if a string's length meets a specified minimum length requirement.
     *
     * @param string $text      The string to be checked.
     * @param int    $minLength The minimum number of characters required.
     *
     * @return bool Returns `true` if the string's length is greater than or equal to `$minLength`,
     *              otherwise returns `false`.
     */
    public static function isMinTextLength($text, $minLength)
    {
        return strlen($text) >= $minLength;
    }

    /**
     * Determines if a given domain string appears to be a subdomain.
     *
     * This check is a simple heuristic that returns true if the string contains
     * more than one dot (`.`), which is a common characteristic of subdomains
     * (e.g., `blog.example.com`).
     *
     * @param string $domain The domain string to check.
     *
     * @return bool Returns `true` if the domain contains more than one dot, `false` otherwise.
     */
    public static function isSubDomain($domain)
    {
        return substr_count($domain, '.') > 1;
    }

    /**
     * Checks if a given URL string contains a path component, indicating a subdirectory.
     *
     * @param string $domain The URL to check.
     *
     * @return bool Returns `true` if the URL has a non-root path (e.g., `example.com/blog`),
     *              `false` otherwise.
     */
    public static function isSubDir($domain)
    {
        $path = parse_url($domain, PHP_URL_PATH);

        return $path && trim($path, '/') !== '';
    }

    /**
     * Validates whether a given string is a well-formed domain name.
     *
     * @param string $text The string to be validated as a domain.
     *
     * @return bool Returns `true` if the string is a valid domain name, `false` otherwise.
     */
    public static function isDomain($text)
    {
        return (bool) filter_var($text, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

    /**
     * Checks if a string ends with one of the provided substrings.
     * The comparison is case-sensitive.
     *
     * @param string $text    The string to check.
     * @param array  $endList An array of substrings to check for at the end of the string.
     *
     * @return bool Returns `true` if the string ends with any of the substrings in the list,
     *              `false` otherwise.
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
     * Checks if a string starts with one of the provided substrings.
     * The comparison is case-sensitive.
     *
     * @param string $text      The string to check.
     * @param array  $startList An array of substrings to check for at the beginning of the string.
     *
     * @return bool Returns `true` if the string starts with any of the substrings in the list,
     *              `false` otherwise.
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
     * Validates whether a given value is numeric.
     *
     * @param mixed $text The value to be checked.
     *
     * @return bool Returns `true` if the value is a number or a numeric string (e.g., "123"),
     *              `false` otherwise.
     */
    public static function isNumber($text)
    {
        return is_numeric($text);
    }

    /**
     * Checks if a value can be interpreted as a boolean.
     *
     * This method considers `true`, `false`, 'true', 'false', 1, 0, '1', and '0'
     * as valid boolean representations, accommodating various forms of input.
     *
     * @param mixed $value The value to be checked.
     *
     * @return bool Returns `true` if the value is a valid boolean representation,
     *              `false` otherwise.
     */
    public static function isBool($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
    }
}
