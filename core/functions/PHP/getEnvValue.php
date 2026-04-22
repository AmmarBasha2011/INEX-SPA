<?php

/**
 * Retrieves the value of a specific key from the application's .env file.
 *
 * This function reads the `.env` file located at the project root, parses it line by line,
 * and returns the value associated with the specified key. It correctly handles
 * comments (lines starting with '#') and ignores empty lines. If the file cannot be
 * read or the key is not found, it returns `null`.
 *
 * @param string $key The name of the environment variable (key) to look for.
 *
 * @return string|null The value of the environment variable as a string, or `null` if the
 *                     key is not found, the file doesn't exist, or an error occurs.
 */
function getEnvValue($key)
{
    static $envCache = null;

    if ($envCache === null) {
        $envPath = __DIR__.'/../../../.env';
        if (!file_exists($envPath)) {
            return null;
        }

        $content = file_get_contents($envPath);
        if ($content === false) {
            return null;
        }

        $envCache = [];
        $lines = preg_split('/\r\n|\r|\n/', $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($k, $v) = explode('=', $line, 2);
                $envCache[trim($k)] = trim($v);
            }
        }
    }

    return $envCache[$key] ?? null;
}
