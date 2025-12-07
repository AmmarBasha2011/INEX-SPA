<?php

/**
 * A simple file-based session management class.
 */
class Session
{
    /**
     * The path to the session storage directory.
     *
     * @var string
     */
    private static $storagePath = __DIR__.'/../../../storage/sessions/';

    /**
     * Creates or updates a session variable.
     *
     * @param string $key   The session key.
     * @param mixed  $value The value to store.
     */
    public static function make($key, $value)
    {
        $data = self::encrypt(json_encode($value));
        file_put_contents(self::$storagePath.$key, $data);
    }

    /**
     * Gets a session variable.
     *
     * @param string $key The session key.
     *
     * @return mixed|null The session data, or null if the session does not exist.
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
     * @param string $key The session key.
     */
    public static function delete($key)
    {
        unlink(self::$storagePath.$key);
    }

    /**
     * Encrypts data.
     *
     * @param string $data The data to encrypt.
     *
     * @return string The encrypted data.
     */
    private static function encrypt($data)
    {
        return base64_encode($data);
    }

    /**
     * Decrypts data.
     *
     * @param string $data The data to decrypt.
     *
     * @return string The decrypted data.
     */
    private static function decrypt($data)
    {
        return base64_decode($data);
    }
}
