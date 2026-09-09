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
        // SECURITY: Sanitize session key — only alphanumeric, dash, underscore
        $key = preg_replace('/[^a-zA-Z0-9_-]/', '', $key);
        if (empty($key)) {
            return false;
        }
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
        // SECURITY: Sanitize session key — only alphanumeric, dash, underscore
        $key = preg_replace('/[^a-zA-Z0-9_-]/', '', $key);
        if (empty($key)) {
            return null;
        }
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
        // SECURITY: Sanitize session key — only alphanumeric, dash, underscore
        $key = preg_replace('/[^a-zA-Z0-9_-]/', '', $key);
        if (empty($key)) {
            return false;
        }
        $file = self::$storagePath.$key;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Obfuscates data using AES-256-CBC encryption with APP_KEY.
     *
     * @param string $data The plain data to be encoded.
     *
     * @return string The encrypted data as base64-encoded string.
     */
    private static function encrypt($data)
    {
        $key = getEnvValue('APP_KEY');
        if (empty($key)) {
            throw new \RuntimeException('APP_KEY is required for session encryption');
        }
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);

        return base64_encode($iv.$encrypted);
    }

    /**
     * Decrypts data from an AES-256-CBC encrypted string.
     *
     * @param string $data The base64-encoded encrypted string.
     *
     * @return string The decrypted, original data.
     */
    private static function decrypt($data)
    {
        $key = getEnvValue('APP_KEY');
        if (empty($key)) {
            throw new \RuntimeException('APP_KEY is required for session decryption');
        }
        $decoded = base64_decode($data);
        $iv = substr($decoded, 0, 16);
        $encrypted = substr($decoded, 16);

        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }
}
