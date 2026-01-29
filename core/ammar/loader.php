<?php

require_once __DIR__.'/Terminal.php';
require_once __DIR__.'/Command.php';

class CommandRegistry
{
    private $commands = [];

    public function register($command)
    {
        $this->commands[$command->getName()] = $command;
    }

    public function getCommand($name)
    {
        return $this->commands[$name] ?? null;
    }

    public function getCommands()
    {
        return $this->commands;
    }
}

$registry = new CommandRegistry();

define('PROJECT_ROOT', dirname(__DIR__, 2));

require_once PROJECT_ROOT.'/core/functions/PHP/executeSQLFilePDO.php';
require_once PROJECT_ROOT.'/core/functions/PHP/getEnvValue.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Cache.php';
require_once PROJECT_ROOT.'/core/functions/PHP/useGemini.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Database.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/SitemapGenerator.php';
require_once PROJECT_ROOT.'/core/functions/PHP/getSlashData.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Session.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/Validation.php';
require_once PROJECT_ROOT.'/core/functions/PHP/classes/UserAuth.php';

function executeStatement($sql, $params = [], $is_return = true)
{
    $DB = new Database();

    return $DB->query($sql, $params, $is_return);
}

// Define constants as in original ammar
define('DB_FOLDER', PROJECT_ROOT.'/db');
define('WEB_FOLDER', PROJECT_ROOT.'/web');
define('CACHE_FOLDER', PROJECT_ROOT.'/core/cache');
define('LANG_FOLDER', PROJECT_ROOT.'/lang');
define('LAYOUT_FOLDER', PROJECT_ROOT.'/layouts');
define('PUBLIC_FOLDER', PROJECT_ROOT.'/public');
define('CRON_TASKS_DIR', PROJECT_ROOT.'/core/cron/tasks/');

// Ensure directories exist
$dirs = [DB_FOLDER, WEB_FOLDER, CACHE_FOLDER, LANG_FOLDER, LAYOUT_FOLDER, PUBLIC_FOLDER, CRON_TASKS_DIR];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Load all command files
$commandFiles = glob(__DIR__.'/commands/*.php');
foreach ($commandFiles as $file) {
    require_once $file;
}

$registry->register(new ListCommand($registry));
