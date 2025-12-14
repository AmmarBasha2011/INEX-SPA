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
 * @return string|null The value of the environment variable as a string, or `null` if the
 *                     key is not found, the file doesn't exist, or an error occurs.
 */
function getEnvValue($key)
{
    // Use realpath to ensure correct path resolution
    $envPath = realpath(__DIR__.'/../../../.env');

    // Early return if file doesn't exist
    if (!$envPath || !file_exists($envPath)) {
        return null;
    }

    try {
        // Read file content
        $content = file_get_contents($envPath);
        if ($content === false) {
            return null;
        }

        // Parse file content
        $lines = preg_split('/\r\n|\r|\n/', $content);

        // Process each line
        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines and comments
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            // Match key-value pair
            if (preg_match('/^'.preg_quote($key).'=(.*)$/i', $line, $matches)) {
                return trim($matches[1]);
            }
        }
    } catch (Exception $e) {
        return null;
    }

    return null;
}
