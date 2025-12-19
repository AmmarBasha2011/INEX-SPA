<?php

/**
 * The main entry point and bootstrap file for the INEX SPA application.
 *
 * This file is responsible for initializing the application environment. It handles:
 * - Loading essential classes and functions.
 * - Reading environment variables from the .env file.
 * - Conditionally including features based on the environment configuration (e.g., database, caching, security).
 * - Setting up error reporting for development mode.
 * - Dynamically loading third-party packages and custom user functions.
 * - Initiating the routing process by calling the `getPage()` function to handle the incoming request.
 */

require_once 'core/functions/PHP/classes/AhmedTemplate.php';
$Ahmed = new AhmedTemplate();
require_once 'core/functions/PHP/getEnvValue.php';
require_once 'core/functions/PHP/redirect.php';

$devMode = getEnvValue('DEV_MODE') == 'true';
$dbUse = getEnvValue('DB_USE') == 'true';
$dbCheck = getEnvValue('DB_CHECK') == 'true';
$useCache = getEnvValue('USE_CACHE') == 'true';
$useRateLimiter = getEnvValue('USE_RATELIMITER') == 'true';
$useCookie = getEnvValue('USE_COOKIE') == 'true';
$detectLanguage = getEnvValue('DETECT_LANGUAGE') == 'true';

if ($devMode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once 'core/functions/PHP/getWEBSITEURLValue.php';
require_once 'core/functions/PHP/getSlashData.php';

if ($dbUse) {
    require_once 'core/functions/PHP/classes/Database.php';
    /**
     * Executes a prepared SQL statement using the database connection.
     *
     * This function is a global wrapper for the `Database::query` method. It provides a
     * convenient way to execute SQL queries without needing to manage the Database
     * object instance directly.
     *
     * @param string $sql       The SQL query to execute, with '?' placeholders for parameters.
     * @param array  $params    An array of values to bind to the placeholders in the SQL query.
     * @param bool   $is_return If `true`, the method will fetch and return all rows from the
     *                          result set. If `false`, it will return a boolean indicating
     *                          the success of the operation (e.g., for INSERT, UPDATE, DELETE).
     *
     * @return array|bool If `$is_return` is `true`, returns an array of associative arrays
     *                    representing the result set. If `$is_return` is `false`, returns
     *                    `true` on successful execution.
     */
    function executeStatement($sql, $params = [], $is_return = true)
    {
        $DB = new Database();

        return $DB->query($sql, $params, $is_return);
    }
    require_once 'core/functions/PHP/runDB.php';
}

require_once 'core/functions/PHP/generateCsrfToken.php';
require_once 'core/functions/PHP/validateCsrfToken.php';
require_once 'core/functions/PHP/getWebsiteUrl.php';

if ($dbCheck && $dbUse) {
    require_once 'core/functions/PHP/executeSQLFilePDO.php';
    foreach (glob('db/*.sql') as $sqlFile) {
        executeSQLFilePDO(
            getEnvValue('DB_HOST'),
            getEnvValue('DB_USER'),
            getEnvValue('DB_PASS'),
            getEnvValue('DB_NAME'),
            $sqlFile
        );
    }
}

if ($useCache) {
    require_once 'core/functions/PHP/classes/Cache.php';
    /**
     * Stores an item in the cache.
     *
     * A global wrapper for the `Cache::set` method, allowing for simple, direct
     * caching of serializable data.
     *
     * @param string $key        The unique key to store the data under.
     * @param mixed  $data       The data to be cached (e.g., string, array, object).
     * @param int    $expiration The cache lifetime in seconds. Defaults to 3600 (1 hour).
     *
     * @return void
     */
    function setCache($key, $data, $expiration = 3600)
    {
        Cache::set($key, $data, $expiration);
    }
    /**
     * Retrieves an item from the cache.
     *
     * A global wrapper for the `Cache::get` method. Returns the stored data if the
     * cache item is valid and not expired.
     *
     * @param string $key The key of the item to retrieve.
     *
     * @return mixed The cached data on success, or `false` if the item does not
     *               exist or has expired.
     */
    function getCache($key)
    {
        return Cache::get($key);
    }
    /**
     * Deletes an item from the cache.
     *
     * A global wrapper for the `Cache::delete` method, providing a simple way
     * to invalidate and remove a cached item.
     *
     * @param string $key The key of the item to delete.
     *
     * @return void
     */
    function deleteCache($key)
    {
        Cache::delete($key);
    }
    /**
     * Updates an existing item in the cache.
     *
     * A global wrapper for the `Cache::update` method. This replaces the data of an
     * existing cache item without changing its original expiration time.
     *
     * @param string $key     The key of the item to update.
     * @param mixed  $newData The new data to store, which must be serializable.
     *
     * @return bool `true` on success, or `false` if the cache item does not exist.
     */
    function updateCache($key, $newData)
    {
        return Cache::update($key, $newData);
    }
}

require_once 'core/functions/PHP/useGemini.php';
if ($useRateLimiter) {
    require_once 'core/functions/PHP/classes/RateLimiter.php';
}
require_once 'core/functions/PHP/classes/SitemapGenerator.php';
if ($useCookie) {
    require_once 'core/functions/PHP/classes/CookieManager.php';
}
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/Session.php';

if ($detectLanguage) {
    require_once 'core/functions/PHP/classes/Language.php';
    $selectedLang = $_COOKIE['lang'] ?? 'en';
    Language::setLanguage($selectedLang);
}
require_once 'core/functions/PHP/classes/Validation.php';
if (getEnvValue('USE_AUTH') == 'true') {
    require_once 'core/functions/PHP/classes/UserAuth.php';
}
if (getEnvValue('USE_FIREWALL') == 'true') {
    require_once 'core/functions/PHP/classes/Firewall.php';
}
if (getEnvValue('USE_SECURITY') == 'true') {
    require_once 'core/functions/PHP/classes/Security.php';
}
if (getEnvValue('USE_LOGGING') == 'true') {
    require_once 'core/functions/PHP/classes/Logger.php';
}
if (getEnvValue('USE_WEBHOOK') == 'true') {
    require_once 'core/functions/PHP/classes/Webhook.php';
}

$packagesJsonPath = __DIR__.'/core/import/package.json';
if (file_exists($packagesJsonPath)) {
    $packagesJson = json_decode(file_get_contents($packagesJsonPath), true);
    if (is_array($packagesJson)) {
        foreach ($packagesJson as $packages) {
            foreach ($packages as $key => $value) {
                require_once __DIR__.'/core/import/'.$key.'/init.php';
            }
        }
    }
}
require_once 'functions.php';
require_once 'core/functions/PHP/getPage.php';
getPage($_GET['page'] ?? '');
