<?php
function getEnvValue($key) {
    // Use realpath to ensure correct path resolution
    $envPath = realpath(__DIR__ . '/../../.env');
    
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
            if (preg_match('/^' . preg_quote($key) . '=(.*)$/i', $line, $matches)) {
                return trim($matches[1]);
            }
        }
    } catch (Exception $e) {
        return null;
    }
    
    return null;
}