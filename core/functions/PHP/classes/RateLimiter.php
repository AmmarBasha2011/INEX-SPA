<?php

/**
 * A simple file-based rate limiter.
 *
 * This class tracks the number of requests made by a user's IP address within
 * a specific time frame and blocks requests that exceed a configurable limit.
 */
class RateLimiter
{
    /**
     * The maximum number of requests allowed per user per time frame.
     * @var int
     */
    private static $limit;

    /**
     * The time frame for rate limiting in seconds (e.g., 3600 for 1 hour).
     * @var int
     */
    private static $timeFrame = 3600; // Time window (1 hour)

    /**
     * The file used to store request counts.
     * @var string
     */
    private static $storageFile = __DIR__.'/../../../storage/rate_limit.json'; // Store request counts

    /**
     * Initializes the rate limiter settings.
     *
     * This method loads the request limit from the environment configuration.
     *
     * @return void
     */
    public static function init()
    {
        self::$limit = getEnvValue('REQUESTS_PER_HOUR'); // Load from environment
    }

    /**
     * Checks if the user has exceeded the rate limit.
     *
     * If the limit is exceeded, it sends a 429 "Too Many Requests" response
     * and terminates the script. Otherwise, it logs the current request.
     *
     * @param string $userIP The IP address of the user making the request.
     * @return void
     */
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
