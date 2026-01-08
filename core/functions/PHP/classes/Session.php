<?php

/**
 * Provides a simple, file-based session management system.
 *
 * This class allows for the storage, retrieval, and deletion of session data.
 * Instead of using PHP's native session handling, it stores each session key-value
 * pair in a separate file within a designated storage directory. This approach
 * is simple but may not be suitable for high-concurrency applications.
 *
 * @warning The "encryption" used in this class is simple base64 encoding, which
 *          is not a form of security and only provides obfuscation. It is highly
 *          recommended to replace this with a proper encryption mechanism (e.g.,
 *          using OpenSSL) if sensitive data is to be stored.
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
     * The value is first serialized into a JSON string to support various data types
     * (arrays, objects, etc.), then "encrypted" (base64 encoded) before being written
     * to a file named after the session key.
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
     * This method reads the corresponding session file from the storage directory,
     * "decrypts" (base64 decodes) its content, and then decodes the JSON string
     * back into its original PHP data type.
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
     * Deletes a session variable by removing its corresponding file from the storage directory.
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
     * @warning This is not a secure method of encryption and provides no confidentiality.
     *          It should be replaced with a strong cryptographic function for any
     *          sensitive data.
     *
     * @param string $data The plain data to be encoded.
     *
     * @return string The base64-encoded data.
     */
    private static function encrypt($data)
    {
        return base64_encode($data); // Simple obfuscation, not true encryption.
    }

    /**
     * Decodes data from a base64 encoded string.
     *
     * This is the counterpart to the `encrypt` method.
     *
     * @param string $data The base64-encoded string.
     *
     * @return string|false The decoded, original data, or `false` on failure.
     */
    private static function decrypt($data)
    {
        return base64_decode($data);
    }
}
