<?php

/**
 * Main entry point and bootstrap file for the INEX SPA application.
 *
 * This file is the front controller for the entire application. It initializes the
 * environment by:
 * 1. Loading essential classes and functions.
 * 2. Reading configuration from the .env file.
 * 3. Conditionally including features like database connections, caching, security,
 *    and rate limiting based on the environment settings.
 * 4. Setting up error reporting for development mode.
 * 5. Dynamically loading third-party packages and custom user-defined functions.
 * 6. Finally, it initiates the routing process by calling `getPage()` to handle the
 *    incoming HTTP request and render the appropriate view.
 */

// Core class and function loading
require_once 'core/functions/PHP/classes/AhmedTemplate.php';
$Ahmed = new AhmedTemplate();
require_once 'core/functions/PHP/getEnvValue.php';
require_once 'core/functions/PHP/redirect.php';

// Environment configuration checks
$devMode = getEnvValue('DEV_MODE') == 'true';
$dbUse = getEnvValue('DB_USE') == 'true';
$dbCheck = getEnvValue('DB_CHECK') == 'true';
$useCache = getEnvValue('USE_CACHE') == 'true';
$useRateLimiter = getEnvValue('USE_RATELIMITER') == 'true';
$useCookie = getEnvValue('USE_COOKIE') == 'true';
$detectLanguage = getEnvValue('DETECT_LANGUAGE') == 'true';

// Enable detailed error reporting in development mode
if ($devMode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Load core utilities
require_once 'core/functions/PHP/getWEBSITEURLValue.php';
require_once 'core/functions/PHP/getSlashData.php';

// Database initialization (if enabled)
if ($dbUse) {
    require_once 'core/functions/PHP/classes/Database.php';
    /**
     * Executes a prepared SQL statement using the application's database connection.
     *
     * This function is a global wrapper for the `Database::query` method, providing a
     * convenient way to execute SQL queries without needing to instantiate the Database
     * class directly. It is only available if `DB_USE` is set to `true` in the .env file.
     *
     * @param string $sql       The SQL query to execute, with '?' placeholders for parameters.
     * @param array  $params    (Optional) An array of values to bind to the placeholders.
     * @param bool   $is_return (Optional) If `true`, the method will fetch and return all rows from the
     *                          result set. If `false`, it's suitable for non-query statements
     *                          (INSERT, UPDATE, DELETE) and will return a boolean.
     *
     * @return array|bool If `$is_return` is `true`, returns an array of results. If `false`,
     *                    returns `true` on success.
     */
    function executeStatement($sql, $params = [], $is_return = true)
    {
        $DB = new Database();
        return $DB->query($sql, $params, $is_return);
    }
    require_once 'core/functions/PHP/runDB.php';
}

// Load security and URL functions
require_once 'core/functions/PHP/generateCsrfToken.php';
require_once 'core/functions/PHP/validateCsrfToken.php';
require_once 'core/functions/PHP/getWebsiteUrl.php';

// Automatic database migration (if enabled)
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

// Cache system and global wrapper functions (if enabled)
if ($useCache) {
    require_once 'core/functions/PHP/classes/Cache.php';
    /**
     * Stores an item in the cache. A global wrapper for `Cache::set`.
     * @param string $key The key for the cache item.
     * @param mixed $data The data to cache.
     * @param int $expiration The cache lifetime in seconds.
     */
    function setCache($key, $data, $expiration = 3600) { Cache::set($key, $data, $expiration); }
    /**
     * Retrieves an item from the cache. A global wrapper for `Cache::get`.
     * @param string $key The key of the item to retrieve.
     * @return mixed The cached data or `false` on failure.
     */
    function getCache($key) { return Cache::get($key); }
    /**
     * Deletes an item from the cache. A global wrapper for `Cache::delete`.
     * @param string $key The key of the item to delete.
     */
    function deleteCache($key) { Cache::delete($key); }
    /**
     * Updates an item in the cache. A global wrapper for `Cache::update`.
     * @param string $key The key of the item to update.
     * @param mixed $newData The new data.
     * @return bool `true` on success, `false` otherwise.
     */
    function updateCache($key, $newData) { return Cache::update($key, $newData); }
}

// Load remaining core and optional classes based on .env configuration
require_once 'core/functions/PHP/useGemini.php';
if ($useRateLimiter) require_once 'core/functions/PHP/classes/RateLimiter.php';
require_once 'core/functions/PHP/classes/SitemapGenerator.php';
if ($useCookie) require_once 'core/functions/PHP/classes/CookieManager.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/Session.php';
if ($detectLanguage) {
    require_once 'core/functions/PHP/classes/Language.php';
    $selectedLang = $_COOKIE['lang'] ?? 'en';
    Language::setLanguage($selectedLang);
}
require_once 'core/functions/PHP/classes/Validation.php';
if (getEnvValue('USE_AUTH') == 'true') require_once 'core/functions/PHP/classes/UserAuth.php';
if (getEnvValue('USE_FIREWALL') == 'true') require_once 'core/functions/PHP/classes/Firewall.php';
if (getEnvValue('USE_SECURITY') == 'true') require_once 'core/functions/PHP/classes/Security.php';
if (getEnvValue('USE_LOGGING') == 'true') require_once 'core/functions/PHP/classes/Logger.php';
if (getEnvValue('USE_WEBHOOK') == 'true') require_once 'core/functions/PHP/classes/Webhook.php';

// Dynamic package loader for third-party libraries
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

// Load custom user functions and initiate the routing
require_once 'functions.php';
require_once 'core/functions/PHP/getPage.php';
getPage($_GET['page'] ?? '');
