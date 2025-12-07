<?php

/**
 * A simple file-based caching class.
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
     * Stores data in the cache.
     *
     * @param string $key        The cache key.
     * @param mixed  $data       The data to be cached.
     * @param int    $expiration The cache lifetime in seconds.
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
     * @param string $key The cache key.
     *
     * @return mixed The cached data, or false if the cache is not found or has expired.
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
     * Updates the data in an existing cache entry without changing the expiration time.
     *
     * @param string $key     The cache key.
     * @param mixed  $newData The new data to be stored.
     *
     * @return bool True on success, false if the cache entry does not exist.
     */
    public static function update($key, $newData)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return false;
        }

        $content = json_decode(file_get_contents($file), true);
        $content['data'] = $newData;

        file_put_contents($file, json_encode($content));

        return true;
    }

    /**
     * Deletes a cache entry.
     *
     * @param string $key The cache key.
     */
    public static function delete($key)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
