<?php

/**
 * A scheduled task for the INEX SPA framework.
 *
 * This class is a template for a custom cron job. It is automatically
 * generated and intended to be used with the framework's cron runner.
 */
class TestTask
{
    /**
     * Handles the execution of the cron task.
     *
     * This method contains the main logic of the task. It ensures logging
     * capabilities are available and performs the task's actions.
     *
     * @return void
     */
    public function handle()
    {
        // Ensure log_cron_message is available
        if (!function_exists('log_cron_message') && file_exists(dirname(__DIR__, 3).'/core/cron/cron_runner.php')) {
            // Attempt to load cron_runner.php's functions if not already loaded,
            // this is a simple way, might need refinement based on cron_runner.php structure
            // require_once dirname(__DIR__, 3) . '/core/cron/cron_runner.php';
            // More robust: define a local fallback if global is not present.

            /**
             * Fallback function to log messages specific to this cron task.
             *
             * @param string $message The message to be logged.
             * @param string $task    The name of the task (defaults to the current task).
             *
             * @return void
             */
            function log_cron_message($message, $task = 'TestTask')
            {
                $logFile = dirname(__DIR__, 3).'/logs/cron.log';
                $timestamp = date('Y-m-d H:i:s');
                $formattedMessage = "[\{$timestamp}] [{$task}] {$message}".PHP_EOL;
                file_put_contents($logFile, $formattedMessage, FILE_APPEND);
            }
        } elseif (!function_exists('log_cron_message')) {
            // Absolute fallback if cron_runner.php also couldn't be sourced or doesn't define it globally

            /**
             * Absolute fallback function to log messages to the cron log file.
             *
             * @param string $message The message to be logged.
             * @param string $task    The name of the task (defaults to the current task).
             *
             * @return void
             */
            function log_cron_message($message, $task = 'TestTask')
            {
                $logFile = dirname(__DIR__, 3).'/logs/cron.log';
                $timestamp = date('Y-m-d H:i:s');
                $formattedMessage = "[\{$timestamp}] [{$task}] {$message}".PHP_EOL;
                file_put_contents($logFile, $formattedMessage, FILE_APPEND);
            }
        }

        $timestamp = date('Y-m-d H:i:s');
        $outputMessage = "TestTask executed successfully at {$timestamp}.";

        echo $outputMessage.PHP_EOL; // Output to console

        // Use the available log_cron_message
        if (function_exists('log_cron_message')) {
            log_cron_message($outputMessage, 'TestTask');
        }

        // TODO: Implement your cron task logic here.
        // Example: Log environment variable
        // if (function_exists('getEnvValue')) {
        //     $appName = getEnvValue('APP_NAME', 'MyApplication');
        //     if (function_exists('log_cron_message')) {
        //         log_cron_message("Application name is '{$appName}'.", 'TestTask');
        //     }
        // } else {
        //     if (function_exists('log_cron_message')) {
        //         log_cron_message("getEnvValue function not available.", 'TestTask');
        //     }
        // }

        if (function_exists('log_cron_message')) {
            log_cron_message('TestTask finished.', 'TestTask');
        }
    }
}
