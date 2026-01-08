<?php

/**
 * Provides a simplified, static interface for managing browser cookies.
 *
 * This utility class wraps PHP's native cookie functions (`setcookie`, `$_COOKIE`),
 * offering a straightforward and consistent way to set, get, delete, and check for
 * the existence of cookies within the application. All cookies are set with a
 * site-wide path (`/`).
 */
class CookieManager
{
    /**
     * Sets a cookie with a specified name, value, and expiration period.
     *
     * @param string $name  The name of the cookie.
     * @param string $value The value to be stored in the cookie. This value is stored on the
     *                      client's computer; do not store sensitive information.
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
     * This method is a wrapper around the `$_COOKIE` superglobal, providing a
     * safe way to access a cookie's value.
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
     * which is the standard method to instruct the browser to remove it immediately.
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
     * Checks whether a cookie with the specified name exists and has a value.
     *
     * @param string $name The name of the cookie to check.
     *
     * @return bool `true` if the cookie exists in the `$_COOKIE` array, `false` otherwise.
     */
    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Retrieves all currently available cookies for the current request.
     *
     * @return array An associative array containing all cookies, where keys are
     *               the cookie names and values are their corresponding values.
     */
    public static function getAll()
    {
        return $_COOKIE;
    }
}
