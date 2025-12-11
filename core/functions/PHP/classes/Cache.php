<?php

/**
 * A simple file-based caching class.
 *
 * This class provides basic functionality to set, get, update, and delete
 * cached data. Caches are stored as serialized JSON files on the filesystem.
 */
class Cache
{
    /**
     * The directory where cache files are stored.
     * @var string
     */
    private static $cacheDir = __DIR__.'/../../../cache/';

    /**
     * Stores data in the cache.
     *
     * @param string $key The unique identifier for the cache item.
     * @param mixed $data The data to be cached. Must be serializable.
     * @param int $expiration The cache lifetime in seconds. Defaults to 3600 (1 hour).
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
     * Retrieves data from the cache.
     *
     * @param string $key The unique identifier for the cache item.
     * @return mixed The cached data, or false if the cache is expired or not found.
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
     * Updates existing data in the cache without changing the expiration time.
     *
     * @param string $key The unique identifier for the cache item.
     * @param mixed $newData The new data to be stored.
     * @return bool True on successful update, false if the cache item does not exist.
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
     * Deletes a cache item.
     *
     * @param string $key The unique identifier for the cache item.
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
