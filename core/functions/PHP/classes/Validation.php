<?php

/**
 * A class for performing various data validations.
 */
class Validation
{
    /**
     * Checks if a string is a valid email address.
     *
     * @param string $text The string to check.
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
     * @return bool True if the string's length is within the limit, false otherwise.
     */
    public static function isTextLength($text, $maxLength)
    {
        return strlen($text) <= $maxLength;
    }

    /**
     * Checks if a string's length is at least a minimum limit.
     *
     * @param string $text      The string to check.
     * @param int    $minLength The minimum allowed length.
     *
     * @return bool True if the string's length is at least the minimum limit, false otherwise.
     */
    public static function isMinTextLength($text, $minLength)
    {
        return strlen($text) >= $minLength;
    }

    /**
     * Checks if a domain is a subdomain.
     *
     * @param string $domain The domain to check.
     *
     * @return bool True if the domain is a subdomain, false otherwise.
     */
    public static function isSubDomain($domain)
    {
        return substr_count($domain, '.') > 1;
    }

    /**
     * Checks if a URL has a subdirectory.
     *
     * @param string $domain The URL to check.
     *
     * @return bool True if the URL has a subdirectory, false otherwise.
     */
    public static function isSubDir($domain)
    {
        return parse_url($domain, PHP_URL_PATH) && trim(parse_url($domain, PHP_URL_PATH), '/') !== '';
    }

    /**
     * Checks if a string is a valid domain name.
     *
     * @param string $text The string to check.
     *
     * @return bool True if the string is a valid domain name, false otherwise.
     */
    public static function isDomain($text)
    {
        return (bool) filter_var($text, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

    /**
     * Checks if a string ends with any of the specified suffixes.
     *
     * @param string $text    The string to check.
     * @param array  $endList An array of suffixes to check for.
     *
     * @return bool True if the string ends with any of the suffixes, false otherwise.
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
     * @param array  $startList An array of prefixes to check for.
     *
     * @return bool True if the string starts with any of the prefixes, false otherwise.
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
     * Checks if a string is a number.
     *
     * @param string $text The string to check.
     *
     * @return bool True if the string is a number, false otherwise.
     */
    public static function isNumber($text)
    {
        return is_numeric($text);
    }

    /**
     * Checks if a value is a boolean.
     *
     * @param mixed $text The value to check.
     *
     * @return bool True if the value is a boolean, false otherwise.
     */
    public static function isBool($text)
    {
        return in_array($text, [true, false, 'true', 'false', 1, 0, '1', '0'], true);
    }
}
