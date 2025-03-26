<?php
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
    function executeStatement($sql, $params = [], $is_return = true) {
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
    function setCache($key, $data, $expiration = 3600) {
        Cache::set($key, $data, $expiration);
    }
    function getCache($key) {
        return Cache::get($key);
    }
    function deleteCache($key) {
        Cache::delete($key);
    }
    function updateCache($key, $newData) {
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

$packagesJsonPath = __DIR__ . '/core/import/package.json';
if (file_exists($packagesJsonPath)) {
    $packagesJson = json_decode(file_get_contents($packagesJsonPath), true);
    if (is_array($packagesJson)) {
        foreach ($packagesJson as $packages) {
            foreach ($packages as $key => $value) {
                require_once __DIR__ . '/core/import/' . $key . '/init.php';
            }
        }
    }
}

require_once 'core/functions/PHP/getPage.php';
getPage($_GET['page'] ?? '');
?>