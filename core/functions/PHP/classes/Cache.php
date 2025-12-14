<?php

/**
 * Manages a simple file-based cache for storing and retrieving serializable data.
 *
 * This class provides static methods to set, get, update, and delete cache entries.
 * Each cache item is stored as a JSON-encoded file on the filesystem, containing the
 * data and its expiration timestamp.
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
     * Creates a cache file containing the data and its expiration time. The data
     * will be JSON-encoded, so it must be serializable.
     *
     * @param string $key        The unique key used to identify the cache item.
     * @param mixed  $data       The data to be cached. This can be any type that is
     *                           serializable to JSON (e.g., string, array, object).
     * @param int    $expiration The lifetime of the cache in seconds from the current time.
     *                           Defaults to 3600 seconds (1 hour).
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
     * Checks if a cache file exists and has not expired. If the cache is valid,
     * it returns the stored data. Otherwise, it deletes the expired file and
     * returns false.
     *
     * @param string $key The unique key of the cache item to retrieve.
     * @return mixed The cached data on success, or `false` if the cache item
     *               does not exist or has expired.
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
     * If the cache item exists, this method replaces its current data with the new
     * data provided. The original expiration timestamp is preserved.
     *
     * @param string $key     The unique key of the cache item to update.
     * @param mixed  $newData The new data to store in the cache. Must be serializable.
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
     * Deletes a specific item from the cache.
     *
     * If a cache file corresponding to the key exists, it will be removed from
     * the filesystem.
     *
     * @param string $key The unique key of the cache item to delete.
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
