<?php

class Validation
{
    public static function isEmail($text)
    {
        return filter_var($text, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isTextLength($text, $maxLength)
    {
        return strlen($text) <= $maxLength;
    }

    public static function isMinTextLength($text, $minLength)
    {
        return strlen($text) >= $minLength;
    }

    public static function isSubDomain($domain)
    {
        return substr_count($domain, '.') > 1;
    }

    public static function isSubDir($domain)
    {
        return parse_url($domain, PHP_URL_PATH) && trim(parse_url($domain, PHP_URL_PATH), '/') !== '';
    }

    public static function isDomain($text)
    {
        return (bool) filter_var($text, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

    public static function isEndWith($text, $endList)
    {
        foreach ($endList as $end) {
            if (str_ends_with($text, $end)) {
                return true;
            }
        }

        return false;
    }

    public static function isStartWith($text, $startList)
    {
        foreach ($startList as $start) {
            if (str_starts_with($text, $start)) {
                return true;
            }
        }

        return false;
    }

    public static function isNumber($text)
    {
        return is_numeric($text);
    }

    public static function isBool($text)
    {
        return $text === true || $text === false || $text === 'true' || $text === 'false' || $text === 1 || $text === 0 || $text === '1' || $text === '0';
    }
}
