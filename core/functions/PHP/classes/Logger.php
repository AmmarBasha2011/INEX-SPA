<?php

/**
 * A simple, file-based logging utility for recording application events.
 *
 * This class provides a static interface to write log messages to different files
 * based on their type (e.g., 'error', 'security', 'api', 'system'). This separation
 * helps in organizing logs and simplifies debugging and monitoring. It also includes
 * a method to clear all log files, which is useful for development or maintenance.
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
     * corresponding log file (e.g., a 'security' type message goes to `security.log`).
     * The operation is atomic due to the use of `FILE_APPEND`.
     *
     * @param string $type    The category of the log message. This determines which file
     *                        the message will be written to. Accepted values are 'error',
     *                        'security', and 'api'. Any other value will default to 'system'.
     * @param string $message The descriptive message to be logged.
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
     * and `api.log` files by overwriting them with an empty string. This is a
     * destructive operation and should be used with caution, primarily in
     * development or testing environments.
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
