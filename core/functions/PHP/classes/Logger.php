<?php

/**
 * Simple File-Based Logging System.
 *
 * This file contains the Logger class, a static utility for writing log messages
 * to various files within the application's log directory.
 */

/**
 * A simple file-based logger.
 *
 * This class provides static methods to log messages to different files based
 * on their type (e.g., 'error', 'security', 'api', 'system'). It also includes
 * a utility method to clear all log files. All methods are static.
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
     * Logs a message to the appropriate log file based on its type.
     *
     * Each log entry is prepended with a timestamp and the log type.
     *
     * @param string $type    The type of the log message, which determines the log file.
     *                        Valid types are 'error', 'security', and 'api'.
     *                        Any other type will default to 'system.log'.
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
     * Clears the contents of all standard log files.
     *
     * This method truncates the 'system.log', 'errors.log', 'security.log',
     * and 'api.log' files.
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
