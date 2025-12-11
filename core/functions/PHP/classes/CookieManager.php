<?php

/**
 * A utility class for managing browser cookies.
 *
 * This class provides a simple, static interface for setting, getting, deleting,
 * and checking for the existence of cookies.
 */
class CookieManager
{
    /**
     * Sets a cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int $days The number of days until the cookie expires. Defaults to 7.
     * @return void
     */
    public static function set($name, $value, $days = 7)
    {
        $expiry = time() + ($days * 24 * 60 * 60);
        setcookie($name, $value, $expiry, '/');
    }

    /**
     * Gets the value of a cookie.
     *
     * @param string $name The name of the cookie.
     * @return string|null The value of the cookie, or null if it does not exist.
     */
    public static function get($name)
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Deletes a cookie.
     *
     * This is done by setting the cookie with an expiration date in the past.
     *
     * @param string $name The name of the cookie to delete.
     * @return void
     */
    public static function delete($name)
    {
        setcookie($name, '', time() - 3600, '/');
    }

    /**
     * Checks if a cookie exists.
     *
     * @param string $name The name of the cookie.
     * @return bool True if the cookie exists, false otherwise.
     */
    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Gets all cookies.
     *
     * @return array An associative array of all cookies.
     */
    public static function getAll()
    {
        return $_COOKIE;
    }
}
