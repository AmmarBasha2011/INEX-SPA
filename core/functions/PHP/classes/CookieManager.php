<?php

/**
 * Provides a simplified, static interface for managing browser cookies.
 *
 * This utility class wraps PHP's native cookie functions, offering a straightforward
 * way to set, get, delete, and check for the existence of cookies within the application.
 */
class CookieManager
{
    /**
     * Sets a cookie with a specified name, value, and expiration period.
     *
     * @param string $name  The name of the cookie.
     * @param string $value The value to be stored in the cookie.
     * @param int    $days  The number of days from the current time that the cookie
     *                      should expire. Defaults to 7 days.
     *
     * @return void
     */
    public static function set($name, $value, $days = 7)
    {
        $expiry = time() + ($days * 24 * 60 * 60);
        setcookie($name, $value, $expiry, '/');
    }

    /**
     * Retrieves the value of a cookie by its name.
     *
     * @param string $name The name of the cookie to retrieve.
     *
     * @return string|null The value of the cookie if it is set, otherwise `null`.
     */
    public static function get($name)
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Deletes a cookie from the browser.
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
     * Checks whether a cookie with the specified name exists.
     *
     * @param string $name The name of the cookie to check.
     *
     * @return bool `true` if the cookie exists, `false` otherwise.
     */
    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Retrieves all currently available cookies.
     *
     * @return array An associative array containing all cookies, where keys are
     *               the cookie names.
     */
    public static function getAll()
    {
        return $_COOKIE;
    }
}
