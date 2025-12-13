<?php

/**
 * Cookie Management Utility.
 *
 * This file provides a static utility class for handling browser cookies,
 * simplifying the process of setting, retrieving, and deleting them.
 */

/**
 * A static utility class for managing browser cookies.
 *
 * Provides a simple, consistent, and testable interface for common cookie
 * operations like setting, getting, deleting, and checking for existence.
 * All methods are static, so no instantiation is required.
 */
class CookieManager
{
    /**
     * Sets a cookie with a specified name, value, and expiration.
     *
     * @param string $name  The name of the cookie.
     * @param string $value The value to be stored. It is recommended to store
     *                      only simple string data.
     * @param int    $days  The number of days from the current time that the
     *                      cookie should expire. Defaults to 7.
     *
     * @return void
     */
    public static function set($name, $value, $days = 7)
    {
        $expiry = time() + ($days * 24 * 60 * 60);
        setcookie($name, $value, $expiry, '/');
    }

    /**
     * Retrieves the value of a specific cookie.
     *
     * @param string $name The name of the cookie to retrieve.
     *
     * @return string|null The value of the cookie if it exists, otherwise null.
     */
    public static function get($name)
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Deletes a cookie.
     *
     * This is achieved by setting the cookie with an expiration date in the past,
     * which instructs the browser to remove it.
     *
     * @param string $name The name of the cookie to delete.
     *
     * @return void
     */
    public static function delete($name)
    {
        setcookie($name, '', time() - 3600, '/');
    }

    /**
     * Checks if a cookie with the specified name exists.
     *
     * @param string $name The name of the cookie to check.
     *
     * @return bool True if the cookie exists in the `$_COOKIE` superglobal, false otherwise.
     */
    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Retrieves all currently available cookies.
     *
     * @return array An associative array containing all cookies sent by the browser.
     */
    public static function getAll()
    {
        return $_COOKIE;
    }
}
