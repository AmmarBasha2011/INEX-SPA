<?php
// core/debug/ErrorHandler.php

// No namespace for now, to keep it simple unless project structure dictates otherwise.
// class ErrorHandler {
// }
// If namespaces are preferred:
// namespace Core\Debug;
// class ErrorHandler {
// }

class ErrorHandler {
    private static $errors = [];
    private static $devMode = false;

    /**
     * Initializes the error handler.
     * @param bool $devMode - Whether development mode is enabled.
     */
    public static function init($devModeEnabled) {
        self::$devMode = $devModeEnabled;
    }

    /**
     * Handles PHP errors (warnings, notices, etc.).
     */
    public static function handleError($errno, $errstr, $errfile, $errline) {
        if (!self::$devMode) {
            return false; // Fall back to standard PHP error handling if not in dev mode
        }

        // Respect error_reporting level
        if (!(error_reporting() & $errno)) {
            return false;
        }

        self::$errors[] = [
            'type' => 'Error',
            'level' => self::mapErrorType($errno),
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ];

        return true; // Error handled by us
    }

    /**
     * Handles uncaught exceptions.
     */
    public static function handleException($exception) {
        if (!self::$devMode) {
            // In a real app, you might want to log this or show a generic error page
            // For now, if not dev mode, let PHP handle it or die silently.
            // Consider what should happen if devMode is false. For now, let's just collect.
             error_log("Exception: " . $exception->getMessage()); // Basic logging
            return;
        }

        self::$errors[] = [
            'type' => 'Exception',
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace() // Or $exception->getTraceAsString()
        ];
        
        // After an exception, especially a fatal one, the script might terminate.
        // We need to ensure errors are displayed. This will be handled by a shutdown function.
        // For now, just collecting is fine for this step.
    }

    /**
     * Registers a shutdown function to display errors if any occurred.
     * This is important for fatal errors that stop script execution.
     */
    public static function registerShutdownHandler() {
        register_shutdown_function(function() {
            if (self::$devMode && self::hasErrors() && php_sapi_name() !== 'cli' && !headers_sent()) {
                // Attempt to clear any output that might have occurred before the error
                // to ensure our script tag is injected cleanly.
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                // Output the error data as JSON within a script tag
                echo "<script id='phpErrorData' type='application/json'>";
                echo json_encode(self::getErrors(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES);
                echo "</script>";
                
                // After outputting the JSON, we should probably stop further script execution if it's a fatal error.
                // Non-fatal errors would have allowed script to continue, but the shutdown function runs at the very end.
                // For fatal errors, PHP would halt anyway. For non-fatal, the page might be partially rendered.
                // The JS overlay will appear on top of whatever content was rendered.
            } else if (self::$devMode && self::hasErrors() && php_sapi_name() === 'cli') {
                // For CLI, just print to stderr (or stdout)
                fwrite(STDERR, "PHP Errors Occurred (CLI):\n");
                fwrite(STDERR, json_encode(self::getErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
            }
        });
    }


    /**
     * Checks if any errors have been collected.
     * @return bool
     */
    public static function hasErrors() {
        return !empty(self::$errors);
    }

    /**
     * Retrieves all collected errors.
     * @return array
     */
    public static function getErrors() {
        return self::$errors;
    }

    /**
     * Clears all collected errors.
     */
    public static function clearErrors() {
        self::$errors = [];
    }
    
    /**
     * Maps PHP error constants to human-readable strings.
     */
    private static function mapErrorType($errno) {
        $map = [
            E_ERROR             => 'E_ERROR',
            E_WARNING           => 'E_WARNING',
            E_PARSE             => 'E_PARSE',
            E_NOTICE            => 'E_NOTICE',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_STRICT            => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED        => 'E_DEPRECATED',
            E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
        ];
        return $map[$errno] ?? (string)$errno;
    }
}
