<?php

/**
 * Manages a simple file-based cache for storing and retrieving serializable data.
 *
 * This class provides a static interface for common cache operations (set, get, update, delete).
 * Each cache item is stored in its own file in a dedicated cache directory. The data is
 * JSON-encoded, allowing for storage of various data types, and includes an expiration
 * timestamp to handle cache invalidation automatically.
 */
class Cache
{
    /**
     * The directory where cache files are stored.
     *
     * @var string
     */
    private static $cacheDir = __DIR__.'/../../../cache/';

    /**
     * Stores a piece of data in the cache for a specified duration.
     *
     * Creates a cache file named with an MD5 hash of the key. The file contains a
     * JSON object with the data and its expiration timestamp. The data provided
     * must be serializable by `json_encode`.
     *
     * @param string $key        The unique key used to identify the cache item.
     * @param mixed  $data       The data to be cached. This can be any type that is
     *                           serializable to JSON (e.g., string, array, object).
     * @param int    $expiration The lifetime of the cache in seconds from the current time.
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
     * Retrieves an item from the cache by its key.
     *
     * Checks if a cache file for the given key exists and has not expired. If the
     * cache is valid, it decodes the JSON and returns the stored data. If the file
     * does not exist or the expiration time has passed, it deletes the expired file
     * and returns false.
     *
     * @param string $key The unique key of the cache item to retrieve.
     *
     * @return mixed The cached data on success, or `false` if the cache item
     *               does not exist, is invalid, or has expired.
     */
    public static function get($key)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return false;
        }

        $content = json_decode(file_get_contents($file), true);
        if (time() > $content['expires']) {
            unlink($file);

            return false;
        }

        return $content['data'];
    }

    /**
     * Updates the data of an existing cache item without altering its expiration time.
     *
     * If a cache item with the specified key exists, this method replaces its current
     * data with the new data provided. The original expiration timestamp is preserved.
     * This is useful for refreshing data without changing its cache lifetime.
     *
     * @param string $key     The unique key of the cache item to update.
     * @param mixed  $newData The new data to store in the cache. Must be serializable.
     *
     * @return bool `true` if the update was successful, or `false` if the cache
     *              item does not exist.
     */
    public static function update($key, $newData)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return false; // No cache to update
        }

        $content = json_decode(file_get_contents($file), true);
        $content['data'] = $newData; // Update data only

        file_put_contents($file, json_encode($content));

        return true;
    }

    /**
     * Deletes a specific item from the cache by its key.
     *
     * If a cache file corresponding to the key exists, it will be removed from
     * the filesystem. This is the primary method for manual cache invalidation.
     *
     * @param string $key The unique key of the cache item to delete.
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
