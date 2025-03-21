<?php
require_once 'core/functions/PHP/redirect.php';
require_once 'core/functions/PHP/getEnvValue.php';
if (getEnvValue('DEV_MODE') == 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
require_once 'core/functions/PHP/getWEBSITEURLValue.php';
require_once 'core/functions/PHP/getSlashData.php';
if (getEnvValue('DB_USE') == 'true') {
    require_once 'core/functions/PHP/classes/Database.php';
    function executeStatement($sql, $params = [], $is_return = true) {
        $DB = new Database();
        return $DB->query($sql, $params, $is_return);
    }
    require_once 'core/functions/PHP/runDB.php';
}
if (getEnvValue('REQUIRED_HTTPS') == 'true' && $_SERVER['HTTPS'] != 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}
require_once 'core/functions/PHP/generateCsrfToken.php';
require_once 'core/functions/PHP/validateCsrfToken.php';
require_once 'core/functions/PHP/getWebsiteUrl.php';
if (getEnvValue('DB_CHECK') == 'true' && getEnvValue('DB_USE') == 'true') {
    require_once 'core/functions/PHP/executeSQLFilePDO.php';
    $sqlFiles = glob("db/*.sql");
    foreach ($sqlFiles as $sqlFile) {
        executeSQLFilePDO(
            getEnvValue('DB_HOST'), 
            getEnvValue('DB_USER'), 
            getEnvValue("DB_PASS"), 
            getEnvValue('DB_NAME'), 
            $sqlFile
        );
    }
}
if (getEnvValue('USE_CACHE') == 'true') {
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
if (getEnvValue('USE_RATELIMITER') == 'true') {
    require_once 'core/functions/PHP/classes/RateLimiter.php';
}
require_once 'core/functions/PHP/classes/SitemapGenerator.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/Session.php';
if (getEnvValue('DETECT_LANGUAGE') == "true") {
    require_once 'core/functions/PHP/classes/Language.php';
    $selectedLang = $_COOKIE['lang'] ?? 'en';
    Language::setLanguage($selectedLang);
}
$packagesJson = json_decode(file_get_contents(__DIR__ . '/core/import/package.json'), true);
foreach ($packagesJson as $packages) {
    foreach ($packages as $key => $value) {
        require __DIR__ . '/core/import/' . $key . '/init.php';
    }
}
require_once 'core/functions/PHP/getPage.php';
getPage(isset($_GET['page']) ? $_GET['page'] : '');
?>