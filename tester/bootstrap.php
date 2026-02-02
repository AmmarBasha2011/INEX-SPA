<?php

// tester/bootstrap.php

// Mock getEnvValue to use .env.test
function getEnvValue($key)
{
    static $env = null;
    if ($env === null) {
        $env = [];
        $lines = file(__DIR__.'/.env.test', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                continue;
            }
            list($name, $value) = explode('=', $line, 2);
            $env[trim($name)] = trim($value);
        }
    }

    return $env[$key] ?? null;
}

// Define some constants that ammar or other parts might use
define('PROJECT_ROOT', dirname(__DIR__));
define('DB_FOLDER', PROJECT_ROOT.'/db');
define('WEB_FOLDER', PROJECT_ROOT.'/web');
define('CACHE_FOLDER', PROJECT_ROOT.'/core/cache');
define('LANG_FOLDER', PROJECT_ROOT.'/lang');
define('LAYOUT_FOLDER', PROJECT_ROOT.'/layouts');
define('PUBLIC_FOLDER', PROJECT_ROOT.'/public');

// Create folders if they don't exist
if (!is_dir(CACHE_FOLDER)) {
    mkdir(CACHE_FOLDER, 0777, true);
}

// Include essential classes and functions
require_once PROJECT_ROOT.'/core/functions/PHP/classes/AhmedTemplate.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Cache.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/CookieManager.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Database.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Firewall.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Language.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Layout.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Logger.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/RateLimiter.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Security.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Session.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/SitemapGenerator.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/UserAuth.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Validation.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Webhook.php';

require_once PROJECT_ROOT.'/core/functions/PHP/generateCsrfToken.php';
require_once PROJECT_ROOT.'/core/functions/PHP/validateCsrfToken.php';
require_once PROJECT_ROOT.'/core/functions/PHP/getWebsiteUrl.php';
require_once PROJECT_ROOT.'/core/functions/PHP/getSlashData.php';
require_once PROJECT_ROOT.'/core/functions/PHP/getWEBSITEURLValue.php';
require_once PROJECT_ROOT.'/core/functions/PHP/redirect.php';

// Global mock for executeStatement
if (!function_exists('executeStatement')) {
    function executeStatement($sql, $params = [], $is_return = true)
    {
        $DB = new Database();

        return $DB->query($sql, $params, $is_return);
    }
}
