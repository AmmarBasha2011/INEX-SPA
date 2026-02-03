<?php

// tester/bootstrap.php

define('PROJECT_ROOT', dirname(__DIR__));
define('DB_FOLDER', PROJECT_ROOT.'/db');
define('WEB_FOLDER', PROJECT_ROOT.'/web');
define('CACHE_FOLDER', PROJECT_ROOT.'/core/cache');
define('LANG_FOLDER', PROJECT_ROOT.'/lang');
define('LAYOUT_FOLDER', PROJECT_ROOT.'/layouts');
define('PUBLIC_FOLDER', PROJECT_ROOT.'/public');
define('CRON_TASKS_DIR', PROJECT_ROOT.'/core/cron/tasks/');

// Mock getEnvValue if needed or load .env.example
if (!function_exists('getEnvValue')) {
    function getEnvValue($key, $default = null)
    {
        static $env = null;
        if ($env === null) {
            $envPath = PROJECT_ROOT.'/.env';
            if (!file_exists($envPath)) {
                $envPath = PROJECT_ROOT.'/.env.example';
            }
            if (file_exists($envPath)) {
                $env = parse_ini_file($envPath);
            } else {
                $env = [];
            }
        }

        return $env[$key] ?? $default;
    }
}

// Load some core files
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Validation.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Cache.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Database.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Security.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Firewall.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Session.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/CookieManager.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Language.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Layout.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Logger.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/RateLimiter.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/SitemapGenerator.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/UserAuth.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Webhook.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/AhmedTemplate.php';

// Mock global variables
$_SESSION = [];
$_COOKIE = [];
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
