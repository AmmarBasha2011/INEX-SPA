<?php

/**
 * Simple File-Based Session Management.
 *
 * This file provides the Session class, a static utility for managing session
 * data using the local filesystem.
 *
 * @warning This implementation uses base64 for encoding, which is not secure encryption.
 *          It is not suitable for storing sensitive information in production.
 */

/**
 * A simple file-based session management class.
 *
 * This class provides a basic mechanism for storing session-like data in files.
 * It uses simple base64 encoding, which is **not secure** and should be
 * replaced with a proper encryption method for production use. All methods are static.
 */
class Session
{
    /**
     * The path to the directory where session files are stored.
     *
     * @var string
     */
    private static $storagePath = __DIR__.'/../../../storage/sessions/';

    /**
     * Creates or overwrites a session variable.
     *
     * The value is JSON encoded and then base64 encoded before being written to a file.
     *
     * @param string $key   The key for the session variable. This will be the filename.
     * @param mixed  $value The value to be stored. Must be serializable.
     *
     * @return void
     */
    public static function make($key, $value)
    {
        $data = self::encrypt(json_encode($value));
        file_put_contents(self::$storagePath.$key, $data);
    }

    /**
     * Retrieves a session variable.
     *
     * Reads the corresponding file, decodes the base64 and JSON, and returns the value.
     *
     * @param string $key The key of the session variable to retrieve.
     *
     * @return mixed|null The value of the session variable, or null if the file does not exist.
     */
    public static function get($key)
    {
        $file = self::$storagePath.$key;
        if (!file_exists($file)) {
            return null;
        }

        return json_decode(self::decrypt(file_get_contents($file)), true);
    }

    /**
     * Deletes a session variable by deleting its corresponding file.
     *
     * @param string $key The key of the session variable to delete.
     *
     * @return void
     */
    public static function delete($key)
    {
        $file = self::$storagePath.$key;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Encodes data using base64.
     *
     * @param string $data The data to be encoded.
     *
     * @return string The base64-encoded data.
     *
     * @warning This is not a secure method of encryption.
     */
    private static function encrypt($data)
    {
        return base64_encode($data);
    }

    /**
     * Decodes data from base64 encoding.
     *
     * @param string $data The base64-encoded data.
     *
     * @return string The decoded data.
     */
    private static function decrypt($data)
    {
        return base64_decode($data);
    }
}
