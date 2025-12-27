<?php

/**
 * Implements a simple, file-based rate limiter to prevent abuse of application endpoints.
 *
 * This class tracks the number of requests from individual IP addresses over a configured
 * time window. It uses a JSON file for storage, making it a dependency-free solution.
 * If an IP exceeds the request limit, subsequent requests are blocked with a
 * 429 "Too Many Requests" HTTP status code, and the script terminates.
 */
class RateLimiter
{
    /**
     * The maximum number of requests allowed from a single IP within the time frame.
     * This value is loaded from the `REQUESTS_PER_HOUR` environment variable via the `init()` method.
     *
     * @var int
     */
    private static $limit;

    /**
     * The duration of the rate-limiting window in seconds.
     * Defaults to 3600 seconds (1 hour).
     *
     * @var int
     */
    private static $timeFrame = 3600;

    /**
     * The path to the JSON file used for storing request timestamps and counts for each IP.
     *
     * @var string
     */
    private static $storageFile = __DIR__.'/../../../storage/rate_limit.json';

    /**
     * Initializes the rate limiter's configuration settings from the environment.
     *
     * This method reads the `REQUESTS_PER_HOUR` value from the .env file to set
     * the request limit. It is called automatically by the `check()` method if not
     * previously initialized.
     *
     * @return void
     */
    public static function init()
    {
        self::$limit = getEnvValue('REQUESTS_PER_HOUR'); // Load from environment
    }

    /**
     * Enforces the rate limit for a given IP address.
     *
     * This is the main method of the rate limiter. It performs the following actions:
     * 1. Reads the request data from the storage file.
     * 2. Cleans up any entries that have expired from the time window.
     * 3. Checks the request count for the provided IP. If the count exceeds the limit,
     *    it sends a 429 HTTP status code and terminates the script with an error message.
     * 4. If the limit is not exceeded, it increments the request count for the IP or initializes it.
     * 5. Writes the updated request data back to the storage file.
     *
     * @param string $userIP The IP address of the client making the request.
     *
     * @return void This method either returns nothing or terminates the script execution.
     */
    public static function check($userIP)
    {
        // Ensure settings are initialized.
        if (!isset(self::$limit)) {
            self::init();
        }

        // Read existing request data.
        $data = file_exists(self::$storageFile) ? json_decode(file_get_contents(self::$storageFile), true) : [];

        // Cleanup expired entries to keep the storage file tidy.
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

        // Save the updated request data.
        file_put_contents(self::$storageFile, json_encode($data));
    }
}
