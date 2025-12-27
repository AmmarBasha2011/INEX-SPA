<?php

/**
 * Provides a simple, file-based session management system.
 *
 * This class allows for the storage, retrieval, and deletion of session data.
 * Each session key corresponds to a separate file in the designated storage directory,
 * offering a persistent session mechanism that does not rely on PHP's native
 * session handling.
 *
 * @warning The "encryption" used in this class is simple base64 encoding, which
 *          is **not secure** and only provides obfuscation. This should be replaced
 *          with a proper encryption mechanism (e.g., using OpenSSL) for any
 *          production environment handling sensitive data.
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
     * The value is JSON-encoded to support various data types (arrays, objects, etc.)
     * and then obfuscated using base64 encoding. The result is written to a file
     * named after the session key in the storage directory.
     *
     * @param string $key   The unique identifier for the session variable. This will be the filename.
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
     * decodes its content from base64, and then decodes it from JSON back into
     * its original PHP data type.
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
     * Deletes a session variable by removing its corresponding file from storage.
     *
     * @param string $key The key of the session variable to delete.
     *
     * @return void
     */
    public static function delete($key)
    {
        $filePath = self::$storagePath . $key;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }


    /**
     * Obfuscates data using base64 encoding for storage.
     *
     * @warning This method **does not provide secure encryption**. It is only for simple
     *          obfuscation. It should be replaced with a strong cryptographic function
     *          (like `openssl_encrypt`) for any sensitive data.
     *
     * @param string $data The plain data to be encoded.
     *
     * @return string The base64-encoded data.
     */
    private static function encrypt($data)
    {
        return base64_encode($data); // This is NOT secure encryption.
    }

    /**
     * Decodes data from a base64 encoded string.
     *
     * This method reverses the obfuscation performed by the `encrypt` method.
     *
     * @param string $data The base64-encoded string to be decoded.
     *
     * @return string The decoded, original data. Returns `false` on failure.
     */
    private static function decrypt($data)
    {
        return base64_decode($data);
    }
}
