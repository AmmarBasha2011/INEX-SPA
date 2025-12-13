<?php

/**
 * File-Based Caching System.
 *
 * Provides a simple and efficient way to store, retrieve, and manage cached
 * data using the local filesystem. Cache items are stored as serialized JSON
 * files and have a defined expiration time. This class is entirely static.
 */
class Cache
{
    /**
     * The directory where cache files are stored.
     *
     * This path is relative to the current file's directory. It points to the
     * `cache` folder in the project's `core` directory.
     *
     * @var string
     */
    private static $cacheDir = __DIR__.'/../../../cache/';

    /**
     * Stores an item in the cache.
     *
     * Creates a cache file with the specified key, storing the provided data
     * along with an expiration timestamp. The data will be JSON encoded.
     *
     * @param string $key        The unique identifier for the cache item.
     * @param mixed  $data       The data to be cached. Must be serializable.
     * @param int    $expiration The cache lifetime in seconds from the current time.
     *                           Defaults to 3600 seconds (1 hour).
     *
     * @return void
     */
    public static function set($key, $data, $expiration = 3600)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        $content = json_encode([
            'expires' => time() + $expiration,
            'data'    => $data,
        ]);
        file_put_contents($file, $content);
    }

    /**
     * Retrieves an item from the cache.
     *
     * Fetches the cache item corresponding to the key. If the item does not exist
     * or has expired, it will be deleted and this method will return false.
     *
     * @param string $key The unique identifier for the cache item.
     *
     * @return mixed The cached data on success, or false if the cache is expired or not found.
     */
    public static function get($key)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return false;
        }

        $content = json_decode(file_get_contents($file), true);
        if (time() > $content['expires']) {
            unlink($file); // The cache has expired, so we delete it.

            return false;
        }

        return $content['data'];
    }

    /**
     * Updates an existing cache item without altering its expiration time.
     *
     * If a cache item with the given key exists, this method replaces its data
     * with the new data provided. The original expiration time is preserved.
     *
     * @param string $key     The unique identifier for the cache item to update.
     * @param mixed  $newData The new data to store in the cache. Must be serializable.
     *
     * @return bool True on successful update, or false if the cache item does not exist.
     */
    public static function update($key, $newData)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return false; // Cannot update a non-existent cache item.
        }

        $content = json_decode(file_get_contents($file), true);
        $content['data'] = $newData; // Only update the data portion.

        file_put_contents($file, json_encode($content));

        return true;
    }

    /**
     * Deletes an item from the cache.
     *
     * Removes the cache file associated with the given key if it exists.
     *
     * @param string $key The unique identifier for the cache item to delete.
     *
     * @return void
     */
    public static function delete($key)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
