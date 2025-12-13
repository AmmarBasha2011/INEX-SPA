<?php

/**
 * Simple IP-Based Rate Limiter.
 *
 * This file contains the RateLimiter class, a static utility for preventing
 * abuse by limiting the number of requests an IP address can make in a given time.
 */

/**
 * A simple file-based rate limiter.
 *
 * This class tracks the number of requests made by a user's IP address within
 * a specific time frame (e.g., one hour) and blocks requests that exceed a
 * configurable limit. It uses a JSON file for storage. All methods are static.
 */
class RateLimiter
{
    /**
     * The maximum number of requests allowed per user per time frame.
     * This is loaded from the `REQUESTS_PER_HOUR` environment variable.
     *
     * @var int
     */
    private static $limit;

    /**
     * The time frame for rate limiting in seconds.
     *
     * @var int
     */
    private static $timeFrame = 3600; // Time window (1 hour)

    /**
     * The path to the JSON file used for storing request counts.
     *
     * @var string
     */
    private static $storageFile = __DIR__.'/../../../storage/rate_limit.json';

    /**
     * Initializes the rate limiter settings.
     *
     * This method loads the request limit from the `REQUESTS_PER_HOUR`
     * environment variable. It should be called before `check()`.
     *
     * @return void
     */
    public static function init()
    {
        self::$limit = (int) getEnvValue('REQUESTS_PER_HOUR');
    }

    /**
     * Checks if the user has exceeded the rate limit.
     *
     * If the rate limit is exceeded for the given IP, this method sends a
     * 429 "Too Many Requests" HTTP response and terminates the script.
     * Otherwise, it logs the current request and allows it to proceed.
     *
     * @param string $userIP The IP address of the user making the request.
     *
     * @return void Terminates script on rate limit violation.
     */
    public static function check($userIP)
    {
        // Ensure the limit is initialized.
        if (!isset(self::$limit)) {
            self::init();
        }

        // Read existing request data from storage.
        $data = file_exists(self::$storageFile) ? json_decode(file_get_contents(self::$storageFile), true) : [];

        // Clean up expired entries from the data array.
        foreach ($data as $ip => $entry) {
            if ($entry['timestamp'] + self::$timeFrame < time()) {
                unset($data[$ip]);
            }
        }

        // Check the request count for the current user's IP.
        if (!isset($data[$userIP])) {
            $data[$userIP] = ['count' => 1, 'timestamp' => time()];
        } else {
            if ($data[$userIP]['count'] >= self::$limit) {
                http_response_code(429); // Too Many Requests
                exit(json_encode(['error' => 'Rate limit exceeded. Try again later.']));
            }
            $data[$userIP]['count']++;
        }

        // Save the updated request data back to the storage file.
        file_put_contents(self::$storageFile, json_encode($data));
    }
}
