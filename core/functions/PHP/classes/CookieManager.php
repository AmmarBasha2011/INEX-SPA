<?php

class CookieManager
{
    public static function set($name, $value, $days = 7)
    {
        $expiry = time() + ($days * 24 * 60 * 60);
        setcookie($name, $value, $expiry, '/');
    }

    public static function get($name)
    {
        return $_COOKIE[$name] ?? null;
    }

    public static function delete($name)
    {
        setcookie($name, '', time() - 3600, '/');
    }

    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    public static function getAll()
    {
        return $_COOKIE;
    }
}
