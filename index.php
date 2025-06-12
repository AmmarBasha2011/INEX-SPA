<?php
require_once 'core/functions/PHP/classes/AhmedTemplate.php';
$Ahmed = new AhmedTemplate();
require_once 'core/functions/PHP/getEnvValue.php';

// Moved from core/functions/PHP/getPage.php
function loadBootstrap() {
    if (getEnvValue('USE_BOOTSTRAP') == 'true') {
        echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
        echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
    }
}

function loadScripts() {
    static $cachedScripts = null;
    if ($cachedScripts === null) {
        ob_start();
        echo "<script src='" . getEnvValue("WEBSITE_URL") . "JS/getWEBSITEURLValue.js'></script>";

        $scripts = [
            'JS/redirect.js',
            'JS/popstate.js',
            'JS/submitData.js',
            'JS/csrfToken.js',
            'JS/submitDataWR.js',
        ];

        if (getEnvValue('USE_COOKIE') == 'true') {
            $scripts[] = 'JS/classes/CookieManager.js'; // Correct way to append an element to an array
        }

        if (getEnvValue('USE_APP_NAME_IN_TITLE') == 'true') {
            $scripts[] = 'JS/addAppNametoHTML.js';
        }

        // Add this new block for motion engine assets
        if (getEnvValue('USE_ANIMATE') == 'true') {
            echo "<link rel='stylesheet' href='" . getEnvValue("WEBSITE_URL") . "css/motion-animations.css'>";
            $scripts[] = 'JS/motion_engine.js'; // Add to the array of scripts
        }

        if (getEnvValue('USE_NOTIFICATION') == 'true') {
            echo "<link rel='stylesheet' href='" . getEnvValue("WEBSITE_URL") . "errors/notification.css'/>";
            $scripts[] = 'JS/showNotification.js';
        }

        foreach ($scripts as $script) {
            echo "<script src='" . getEnvValue("WEBSITE_URL") . $script . "'></script>";
        }

        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';

        $cachedScripts = ob_get_clean();
    }
    echo $cachedScripts;
}
// End of moved functions

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
require_once 'core/functions/PHP/classes/Validation.php';
if (getEnvValue("USE_AUTH") == "true") {
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
require_once 'functions.php';

// --- ROUTING LOGIC ---
$useNewRoutes = getEnvValue('USE_NEW_ROUTES');

// Default to true if the .env variable is missing or empty
if ($useNewRoutes === null || $useNewRoutes === '') {
    $useNewRoutes = 'true';
}

if (strtolower($useNewRoutes) === 'true') {
    // Use the New Routing System
    require_once 'routes.php';    // Load the route definitions
    require_once 'core/Router.php'; // Load the Router class

    // Ensure $Ahmed is available (it's initialized at the top of index.php)
    $router = new Router($routes, $Ahmed);
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $router->dispatch($requestUri, $requestMethod);

} else {
    // Use the Old (Legacy) Routing System
    // getSlashData.php is needed by getPage.php
    require_once 'core/functions/PHP/getSlashData.php';
    require_once 'core/functions/PHP/getPage.php';

    // The old system relies on $_GET['page']
    $page = $_GET['page'] ?? '';
    getPage($page);
}
// --- END ROUTING LOGIC ---
?>