<?php

/**
 * A simple, file-based logging utility for recording application events.
 *
 * This class provides static methods to write log messages to different, categorized
 * log files (e.g., 'error', 'security', 'api', 'system'). This separation helps in
 * organizing log data for easier debugging and monitoring. The class also includes a
 * utility method to clear all log files, which can be useful during development or
 * for maintenance tasks.
 */
class Logger
{
    /**
     * The absolute path to the directory where log files are stored.
     *
     * @var string
     */
    private static $logPath = __DIR__.'/../../../logs/';

    /**
     * Writes a message to a log file determined by its type.
     *
     * Each log entry is automatically timestamped and formatted before being appended to the
     * corresponding log file (e.g., `errors.log`, `security.log`). If an invalid type
     * is provided, the message will be written to the default `system.log`.
     *
     * @param string $type    The category of the log message. This determines which file
     *                        the message will be written to. Accepted values are 'error',
     *                        'security', and 'api'. Any other value will default to 'system'.
     * @param string $message The descriptive message or data to be logged.
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
     * Clears the content of all predefined log files.
     *
     * This method truncates the `system.log`, `errors.log`, `security.log`,
     * and `api.log` files by overwriting them with an empty string, effectively
     * deleting all their logged messages.
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
