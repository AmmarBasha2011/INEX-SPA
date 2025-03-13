<?php
function getEnvValue($key) {
    $envPath = __DIR__ . '/../../.env';
    if (!file_exists($envPath)) {
        return null;
    }
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments
        if (strpos($line, '#') === 0) {
            continue;
        }
        // Split the line into key and value
        $parts = explode('=', $line, 2);
        if (count($parts) == 2 && trim($parts[0]) === $key) {
            return trim($parts[1]);
        }
    }
    return null;
}
?>