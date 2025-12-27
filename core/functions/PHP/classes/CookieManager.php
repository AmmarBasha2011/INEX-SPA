<?php

/**
 * Provides a simplified, static interface for managing browser cookies.
 *
 * This utility class wraps PHP's native cookie functions (`setcookie`, `$_COOKIE`),
 * offering a straightforward and consistent API to set, get, delete, and check
 * for the existence of cookies within the application. All cookies are set with a
 * site-wide path ('/').
 */
class CookieManager
{
    /**
     * Sets a cookie with a specified name, value, and expiration period.
     *
     * @param string $name  The name of the cookie. This will be the key in the `$_COOKIE` array.
     * @param string $value The value to be stored in the cookie.
     * @param int    $days  The number of days from the current time until the cookie
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
     * @return string|null The value of the cookie if it is set, otherwise returns `null`.
     */
    public static function get($name)
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Deletes a cookie from the browser.
     *
     * This method effectively removes a cookie by setting it with an expiration date
     * in the past, which instructs the browser to delete it immediately.
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
     * Checks whether a cookie with the specified name exists and is available.
     *
     * @param string $name The name of the cookie to check.
     *
     * @return bool Returns `true` if the cookie is set in the `$_COOKIE` superglobal,
     *              `false` otherwise.
     */
    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Retrieves all currently available cookies as an associative array.
     *
     * @return array An associative array containing all cookies sent by the browser,
     *               where the keys are the cookie names and the values are their contents.
     */
    public static function getAll()
    {
        return $_COOKIE;
    }
}
