<?php

class RateLimiter
{
    private static $limit;
    private static $timeFrame = 3600; // Time window (1 hour)
    private static $storageFile = __DIR__.'/../../../storage/rate_limit.json'; // Store request counts

    public static function init()
    {
        self::$limit = getEnvValue('REQUESTS_PER_HOUR'); // Load from environment
    }

    public static function check($userIP)
    {
        // Ensure init() is called
        if (!isset(self::$limit)) {
            self::init();
        }

        // Read existing data
        $data = file_exists(self::$storageFile) ? json_decode(file_get_contents(self::$storageFile), true) : [];

        // Cleanup expired entries
        foreach ($data as $ip => $entry) {
            if ($entry['timestamp'] + self::$timeFrame < time()) {
                unset($data[$ip]);
            }
        }

        // Check user request count
        if (!isset($data[$userIP])) {
            $data[$userIP] = ['count' => 1, 'timestamp' => time()];
        } else {
            if ($data[$userIP]['count'] >= self::$limit) {
                http_response_code(429); // Too Many Requests
                exit(json_encode(['error' => 'Rate limit exceeded. Try again later.']));
            }
            $data[$userIP]['count']++;
        }

        // Save updated data
        file_put_contents(self::$storageFile, json_encode($data));
    }
}
