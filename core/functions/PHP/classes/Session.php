<?php

/**
 * A simple file-based session management class.
 *
 * This class provides a basic mechanism for storing session data in files.
 * It uses simple base64 encoding for "encryption," which is not secure
 * and should be improved for production use.
 */
class Session
{
    /**
     * The path to the directory where session files are stored.
     * @var string
     */
    private static $storagePath = __DIR__.'/../../../storage/sessions/';

    /**
     * Creates or overwrites a session variable.
     *
     * @param string $key The key for the session variable.
     * @param mixed $value The value to be stored.
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
     * @param string $key The key of the session variable to retrieve.
     * @return mixed|null The value of the session variable, or null if not found.
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
     * Deletes a session variable.
     *
     * @param string $key The key of the session variable to delete.
     * @return void
     */
    public static function delete($key)
    {
        unlink(self::$storagePath.$key);
    }

    /**
     * "Encrypts" data using base64 encoding.
     *
     * Note: This is not a secure method of encryption.
     *
     * @param string $data The data to be encoded.
     * @return string The base64-encoded data.
     */
    private static function encrypt($data)
    {
        return base64_encode($data); // Simple encryption (can be improved)
    }

    /**
     * Decrypts data from base64 encoding.
     *
     * @param string $data The base64-encoded data.
     * @return string The decoded data.
     */
    private static function decrypt($data)
    {
        return base64_decode($data);
    }
}
