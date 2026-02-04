<?php

/**
 * An example cron task to demonstrate the basic structure of a scheduled job.
 *
 * This class serves as a template for creating new cron tasks. It is designed to be
 * executed by the `cron_runner.php` script, which calls the `handle()` method.
 */
class ExampleTask
{
    /**
     * The main execution method for the cron task.
     *
     * This method contains the logic that will be performed when the cron job is run.
     * In this example, it logs messages to the console and the cron log file,
     * simulates a workflow, and demonstrates how to access environment variables.
     *
     * @return void
     */
    public function handle()
    {
        // Access the global log_cron_message function if it's available
        // or use a dedicated logger if your application has one.
        // For this example, we assume log_cron_message from cron_runner.php is accessible
        // or we define a simple one if not.

        if (!function_exists('log_cron_message')) {
            /**
             * Fallback logging function for the example task.
             *
             * @param string $message The message to be logged to the cron log file.
             *
             * @return void
             */
            function log_cron_message($message)
            {
                $logFile = dirname(__DIR__, 2).'/logs/cron.log'; // Adjust path if necessary
                $timestamp = date('Y-m-d H:i:s');
                file_put_contents($logFile, "[{$timestamp}] [ExampleTask] {$message}".PHP_EOL, FILE_APPEND);
            }
        }

        $timestamp = date('Y-m-d H:i:s');
        $message = "ExampleTask executed successfully at {$timestamp}.";

        echo $message.PHP_EOL; // Output to console (useful for manual runs)
        log_cron_message($message); // Log to cron.log

        // Example of accessing an environment variable
        // Ensure getEnvValue is loaded by cron_runner.php or handle its absence
        if (function_exists('getEnvValue')) {
            $appName = getEnvValue('APP_NAME', 'MyApplication');
            log_cron_message("ExampleTask: Application name is '{$appName}'.");
        } else {
            log_cron_message('ExampleTask: getEnvValue function not available.');
        }

        // Simulate some work
        // For a real task, this is where you'd put database operations,
        // email sending, file processing, API calls, etc.
        for ($i = 0; $i < 3; $i++) {
            log_cron_message('ExampleTask: Doing work... step '.($i + 1));
            // sleep(1); // Uncomment to simulate longer work
        }

        log_cron_message('ExampleTask finished.');
    }
}
