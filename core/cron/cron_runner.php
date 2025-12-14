<?php

/**
 * A command-line script for executing scheduled cron tasks.
 *
 * This script acts as the main entry point for running cron jobs. It is designed
 * to be called from the command line (or a crontab entry) with the name of a
 * task class as an argument. It handles the loading of the necessary environment,
 * locating and instantiating the specified task class, and executing its `handle`
 * method. It also includes basic logging for monitoring task execution.
 *
 * @usage php cron_runner.php <TaskName>
 * @example php /path/to/project/core/cron/cron_runner.php MyDailyReport
 */

// Ensure script is run from CLI
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('This script can only be run from the command line.');
}

// Basic error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define project root
define('PROJECT_ROOT', dirname(__DIR__, 2));

// Path to the tasks directory
define('TASKS_DIR', PROJECT_ROOT.'/core/cron/tasks/');
define('LOGS_DIR', PROJECT_ROOT.'/core/logs/');

// --- Environment Variable Loading (Simplified - adjust as per actual project structure) ---
if (file_exists(PROJECT_ROOT.'/core/functions/PHP/getEnvValue.php')) {
    require_once PROJECT_ROOT.'/core/functions/PHP/getEnvValue.php';
} else {
    // Fallback or error if .env loading mechanism isn't found
    // For now, we'll assume it might not be strictly necessary for all tasks
    // or tasks will handle their own config if this file is missing.
    // You might need to adjust this based on your project's .env handling
    if (!function_exists('getEnvValue')) {
        function getEnvValue($key, $default = null)
        {
            // A very basic fallback if the real getEnvValue is missing
            $value = getenv($key);

            return $value !== false ? $value : $default;
        }
    }
}

// --- Logger Setup (Simplified) ---
// Ensure the logs directory and cron.log file exist and are writable.
// The actual cron job setup on the server should handle output redirection
// to this file, but the script can also try to log directly.
$logFile = LOGS_DIR.'cron.log';

if (!is_dir(LOGS_DIR)) {
    // Try to create the logs directory if it doesn't exist.
    mkdir(LOGS_DIR, 0755, true);
}

/**
 * Appends a message to the cron log file.
 *
 * This function adds a timestamp to the given message and writes it to the
 * `cron.log` file, providing a simple logging mechanism for cron job execution.
 *
 * @param string $message The message to be logged.
 * @return void
 */
function log_cron_message($message)
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[{$timestamp}] {$message}".PHP_EOL, FILE_APPEND);
}

// --- Task Execution Logic ---
if ($argc < 2) {
    log_cron_message('Error: No task name provided.');
    echo 'Usage: php cron_runner.php <TaskName>'.PHP_EOL;
    exit(1);
}

$taskName = $argv[1];
$taskFile = TASKS_DIR.$taskName.'.php';

if (!file_exists($taskFile)) {
    log_cron_message("Error: Task file '{$taskFile}' not found for task '{$taskName}'.");
    echo "Error: Task '{$taskName}' not found.".PHP_EOL;
    exit(1);
}

// Include necessary core files (adjust based on actual dependencies)
// This is a placeholder. You'll need to identify common dependencies
// for your cron tasks, similar to how index.php includes files.
// Example:
// require_once PROJECT_ROOT . '/core/functions/PHP/classes/Database.php';
// require_once PROJECT_ROOT . '/core/functions/PHP/classes/Logger.php'; // If you have a dedicated logger

log_cron_message("Attempting to run task: {$taskName}");

try {
    require_once $taskFile;
    if (class_exists($taskName)) {
        $taskInstance = new $taskName();
        if (method_exists($taskInstance, 'handle')) {
            $taskInstance->handle();
            log_cron_message("Successfully executed task: {$taskName}");
            echo "Task '{$taskName}' executed successfully.".PHP_EOL;
        } else {
            log_cron_message("Error: Task '{$taskName}' class does not have a 'handle' method.");
            echo "Error: Task '{$taskName}' is not properly configured (missing handle method).".PHP_EOL;
            exit(1);
        }
    } else {
        log_cron_message("Error: Class '{$taskName}' not found in '{$taskFile}'.");
        echo "Error: Task '{$taskName}' class not found.".PHP_EOL;
        exit(1);
    }
} catch (Throwable $e) { // Catching Throwable for broader error catching (PHP 7+)
    log_cron_message("Error during task '{$taskName}': ".$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
    echo "Error executing task '{$taskName}': ".$e->getMessage().PHP_EOL;
    exit(1);
}

exit(0);
