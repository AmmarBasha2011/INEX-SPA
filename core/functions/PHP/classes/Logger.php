<?php

/**
 * A simple file-based logger.
 *
 * This class provides static methods to log messages to different files based
 * on their type (e.g., 'error', 'security') and to clear all log files.
 */
class Logger
{
    /**
     * The path to the directory where log files are stored.
     *
     * @var string
     */
    private static $logPath = __DIR__.'/../../../logs/';

    /**
     * Logs a message to the appropriate log file.
     *
     * @param string $type    The type of the log message. Determines the log file used.
     *                        Can be 'error', 'security', 'api', or a default 'system'.
     * @param string $message The message to be logged.
     *
     * @return void
     */
    public static function log($type, $message)
    {
        $date = date('Y-m-d H:i:s');
        $entry = "[$date] [$type] $message".PHP_EOL;

        switch ($type) {
            case 'error':
                file_put_contents(self::$logPath.'errors.log', $entry, FILE_APPEND);
                break;
            case 'security':
                file_put_contents(self::$logPath.'security.log', $entry, FILE_APPEND);
                break;
            case 'api':
                file_put_contents(self::$logPath.'api.log', $entry, FILE_APPEND);
                break;
            default:
                file_put_contents(self::$logPath.'system.log', $entry, FILE_APPEND);
        }
    }

    /**
     * Clears all log files.
     *
     * This method empties the contents of all standard log files.
     *
     * @return void
     */
    public static function clearLogs()
    {
        $files = ['system.log', 'errors.log', 'security.log', 'api.log'];
        foreach ($files as $file) {
            $path = self::$logPath.$file;
            if (file_exists($path)) {
                file_put_contents($path, ''); // Empty the file
            }
        }
    }
}
