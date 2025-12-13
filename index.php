<?php
/**
 * INEX SPA Framework - Main Entry Point
 *
 * This file serves as the central entry point and bootstrap for the entire
 * INEX SPA application. It handles configuration, initializes core components,
 * and routes all incoming requests.
 *
 * The bootstrap process includes:
 * - Loading the AhmedTemplate engine.
 * - Reading environment variables from the .env file.
 * - Enabling error reporting in development mode.
 * - Conditionally loading components like the Database, Cache, and Rate Limiter
 *   based on .env settings.
 * - Running database migrations if enabled.
 * - Initializing the session, language detection, and security features.
 * - Loading any third-party packages.
 * - Including custom user functions.
 * - Passing the request to the main `getPage` routing function.
 *
 * @package INEX
 */

// Core component loading
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

// Enable error reporting in development mode
if ($devMode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Load essential helper functions
require_once 'core/functions/PHP/getWEBSITEURLValue.php';
require_once 'core/functions/PHP/getSlashData.php';

// Database initialization
if ($dbUse) {
    require_once 'core/functions/PHP/classes/Database.php';
    /**
     * A global helper function to execute a database query.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params Parameters to bind to the query.
     * @param bool $is_return Whether to return a result set.
     * @return array|bool The result set or success status.
     */
    function executeStatement($sql, $params = [], $is_return = true)
    {
        $DB = new Database();
        return $DB->query($sql, $params, $is_return);
    }
    require_once 'core/functions/PHP/runDB.php';
}

// CSRF token and URL functions
require_once 'core/functions/PHP/generateCsrfToken.php';
require_once 'core/functions/PHP/validateCsrfToken.php';
require_once 'core/functions/PHP/getWebsiteUrl.php';

// Automatic database migration runner
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

// Cache system initialization
if ($useCache) {
    require_once 'core/functions/PHP/classes/Cache.php';
    /**
     * Global helper to store an item in the cache.
     * @param string $key The unique identifier for the cache item.
     * @param mixed $data The data to be cached.
     * @param int $expiration The cache lifetime in seconds. Defaults to 3600.
     */
    function setCache($key, $data, $expiration = 3600) { Cache::set($key, $data, $expiration); }
    /**
     * Global helper to retrieve an item from the cache.
     * @param string $key The unique identifier for the cache item.
     * @return mixed The cached data or false if not found/expired.
     */
    function getCache($key) { return Cache::get($key); }
    /**
     * Global helper to delete an item from the cache.
     * @param string $key The unique identifier for the cache item.
     */
    function deleteCache($key) { Cache::delete($key); }
    /**
     * Global helper to update an existing cache item.
     * @param string $key The unique identifier for the cache item.
     * @param mixed $newData The new data to store.
     * @return bool True on success, false if the item does not exist.
     */
    function updateCache($key, $newData) { return Cache::update($key, $newData); }
}

// Load other core classes and utilities
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

// Language detection and initialization
if ($detectLanguage) {
    require_once 'core/functions/PHP/classes/Language.php';
    $selectedLang = $_COOKIE['lang'] ?? 'en';
    Language::setLanguage($selectedLang);
}

// Load security and authentication components
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

// Third-party package loader
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

// Load custom user-defined functions
require_once 'functions.php';

// Route the incoming request
require_once 'core/functions/PHP/getPage.php';
getPage($_GET['page'] ?? '');
