<?php

/**
 * Provides a simple, file-based session management system.
 *
 * This class allows for the storage, retrieval, and deletion of session data,
 * with each session key corresponding to a separate file in the designated
 * storage directory.
 *
 * @warning The "encryption" used in this class is simple base64 encoding, which
 *          is not secure and only provides obfuscation. This should be replaced
 *          with a proper encryption mechanism for production environments.
 */
class Session
{
    /**
     * The absolute path to the directory where session files are stored.
     *
     * @var string
     */
    private static $storagePath = __DIR__.'/../../../storage/sessions/';

    /**
     * Creates or overwrites a session variable with the given value.
     *
     * The value is JSON-encoded to support various data types and then "encrypted"
     * before being written to a file named after the key.
     *
     * @param string $key   The unique identifier for the session variable.
     * @param mixed  $value The data to be stored. This should be a type that can
     *                      be serialized by `json_encode`.
     *
     * @return void
     */
    public static function make($key, $value)
    {
        $data = self::encrypt(json_encode($value));
        file_put_contents(self::$storagePath.$key, $data);
    }

    /**
     * Retrieves the value of a session variable by its key.
     *
     * Reads the corresponding session file, "decrypts" its content, and decodes
     * it from JSON back into its original PHP data type.
     *
     * @param string $key The key of the session variable to retrieve.
     *
     * @return mixed|null The stored session data, or `null` if the session
     *                    file does not exist.
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
     * Deletes a session variable by removing its corresponding file.
     *
     * @param string $key The key of the session variable to delete.
     *
     * @return void
     */
    public static function delete($key)
    {
        unlink(self::$storagePath.$key);
    }

    /**
     * Obfuscates data using base64 encoding.
     *
     * @warning This is not a secure method of encryption. It should be replaced
     *          with a strong cryptographic function for any sensitive data.
     *
     * @param string $data The plain data to be encoded.
     *
     * @return string The base64-encoded data.
     */
    private static function encrypt($data)
    {
        return base64_encode($data); // Simple encryption (can be improved)
    }

    /**
     * Decodes data from a base64 encoded string.
     *
     * @param string $data The base64-encoded string.
     *
     * @return string The decoded, original data.
     */
    private static function decrypt($data)
    {
        return base64_decode($data);
    }
}
