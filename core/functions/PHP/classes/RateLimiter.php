<?php

/**
 * Implements a simple, file-based rate limiter to prevent abuse of application endpoints.
 *
 * This class provides a static interface to track the number of requests from individual
 * IP addresses over a defined time window. If an IP exceeds a configured request limit,
 * subsequent requests are blocked with a 429 "Too Many Requests" HTTP status code,
 * helping to mitigate brute-force attacks and service abuse.
 */
class RateLimiter
{
    /**
     * The maximum number of requests allowed from a single IP address within the time frame.
     * This value is loaded from the `REQUESTS_PER_HOUR` environment variable.
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
    private static $timeFrame = 3600; // Time window (1 hour)

    /**
     * The path to the JSON file used for storing request timestamps and counts for each IP.
     *
     * @var string
     */
    private static $storageFile = __DIR__.'/../../../storage/rate_limit.json'; // Store request counts

    /**
     * Initializes the rate limiter's configuration settings.
     *
     * This method reads the `REQUESTS_PER_HOUR` value from the .env file to set the request
     * limit. It is called automatically by the `check` method if not previously initialized.
     *
     * @return void
     */
    public static function init()
    {
        self::$limit = getEnvValue('REQUESTS_PER_HOUR'); // Load from environment
    }

    /**
     * Checks the request rate for a given IP address and blocks it if the limit is exceeded.
     *
     * This is the core method of the rate limiter. It performs the following actions:
     * 1. Initializes settings from the .env file if they haven't been loaded.
     * 2. Reads the request data (IPs, counts, timestamps) from the storage file.
     * 3. Cleans up any entries that have expired (are older than the `timeFrame`).
     * 4. Checks the current request count for the provided IP. If it exceeds the limit,
     *    it terminates the script with a 429 HTTP status and a JSON error message.
     * 5. If the limit is not exceeded, it increments the request count for the IP.
     * 6. Writes the updated request data back to the storage file.
     *
     * @param string $userIP The IP address of the client making the request, typically
     *                       from `$_SERVER['REMOTE_ADDR']`.
     *
     * @return void This method either allows script execution to continue or terminates it.
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
