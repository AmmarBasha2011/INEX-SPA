<?php

/**
 * Gets the value of an environment variable from the .env file.
 *
 * @param string $key The name of the environment variable.
 *
 * @return string|null The value of the environment variable, or null if it is not found.
 */
function getEnvValue($key)
{
    $envPath = realpath(__DIR__.'/../../../.env');

    if (!$envPath || !file_exists($envPath)) {
        return null;
    }

    try {
        $content = file_get_contents($envPath);
        if ($content === false) {
            return null;
        }

        $lines = preg_split('/\r\n|\r|\n/', $content);

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            if (preg_match('/^'.preg_quote($key).'=(.*)$/i', $line, $matches)) {
                return trim($matches[1]);
            }
        }
    } catch (Exception $e) {
        return null;
    }

    return null;
}
