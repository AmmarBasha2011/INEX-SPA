<?php

/**
 * Provides a collection of static methods for common data validation tasks.
 *
 * This utility class offers a set of convenient, reusable methods for validating
 * various data formats, such as email addresses, string lengths, domains, and
 * numeric types. These checks are essential for ensuring data integrity and
 * security throughout the application.
 */
class Validation
{
    /**
     * Validates whether a given string is a well-formed email address.
     *
     * This method uses PHP's built-in `filter_var` function with the `FILTER_VALIDATE_EMAIL`
     * filter, which is a reliable way to check for standard email address syntax.
     *
     * @param string $text The string to be validated as an email.
     *
     * @return bool `true` if the string is a valid email, `false` otherwise.
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
     * @return bool `true` if the string's length is less than or equal to `$maxLength`,
     *              `false` otherwise.
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
     * @return bool `true` if the string's length is greater than or equal to `$minLength`,
     *              `false` otherwise.
     */
    public static function isMinTextLength($text, $minLength)
    {
        return strlen($text) >= $minLength;
    }

    /**
     * Determines if a given domain string is a subdomain.
     *
     * This check is based on a simple heuristic: a domain is considered a subdomain
     * if it contains more than one dot (e.g., 'sub.example.com').
     *
     * @param string $domain The domain string to check.
     *
     * @return bool `true` if the domain appears to be a subdomain, `false` otherwise.
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
     * @return bool `true` if the URL has a non-root path (e.g., 'example.com/path'),
     *              `false` otherwise.
     */
    public static function isSubDir($domain)
    {
        return parse_url($domain, PHP_URL_PATH) && trim(parse_url($domain, PHP_URL_PATH), '/') !== '';
    }

    /**
     * Validates whether a given string is a well-formed domain name.
     *
     * This uses PHP's `filter_var` with the `FILTER_VALIDATE_DOMAIN` flag for a
     * robust and standards-compliant domain validation.
     *
     * @param string $text The string to be validated as a domain.
     *
     * @return bool `true` if the string is a valid domain name, `false` otherwise.
     */
    public static function isDomain($text)
    {
        return (bool) filter_var($text, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

    /**
     * Checks if a string ends with one of the provided substrings.
     *
     * The comparison is case-sensitive.
     *
     * @param string $text    The string to check.
     * @param array  $endList An array of substrings to check for at the end of the string.
     *
     * @return bool `true` if the string ends with any of the substrings in the list,
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
     *
     * The comparison is case-sensitive.
     *
     * @param string $text      The string to check.
     * @param array  $startList An array of substrings to check for at the beginning of the string.
     *
     * @return bool `true` if the string starts with any of the substrings in the list,
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
     * This uses PHP's `is_numeric` function, which returns true for integers, floats,
     * and numeric strings.
     *
     * @param mixed $text The value to be checked.
     *
     * @return bool `true` if the value is a number or a numeric string, `false` otherwise.
     */
    public static function isNumber($text)
    {
        return is_numeric($text);
    }

    /**
     * Checks if a value can be interpreted as a boolean.
     *
     * This method provides a flexible boolean check, considering `true`, `false`,
     * the strings 'true' and 'false', the integers 1 and 0, and the strings '1' and '0'
     * as valid boolean representations.
     *
     * @param mixed $text The value to be checked.
     *
     * @return bool `true` if the value is a valid boolean representation, `false` otherwise.
     */
    public static function isBool($text)
    {
        return in_array($text, [true, false, 'true', 'false', 1, 0, '1', '0'], true);
    }
}
