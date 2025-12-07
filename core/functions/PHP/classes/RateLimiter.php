<?php

/**
 * A simple rate limiter class.
 */
class RateLimiter
{
    /**
     * The maximum number of requests allowed per hour.
     *
     * @var int
     */
    private static $limit;

    /**
     * The time frame for the rate limit in seconds.
     *
     * @var int
     */
    private static $timeFrame = 3600;

    /**
     * The path to the storage file for rate limit data.
     *
     * @var string
     */
    private static $storageFile = __DIR__.'/../../../storage/rate_limit.json';

    /**
     * Initializes the rate limiter, loading the request limit from the environment.
     */
    public static function init()
    {
        self::$limit = getEnvValue('REQUESTS_PER_HOUR');
    }

    /**
     * Checks the rate limit for a given IP address.
     *
     * @param string $userIP The IP address to check.
     */
    public static function check($userIP)
    {
        if (!isset(self::$limit)) {
            self::init();
        }

        $data = file_exists(self::$storageFile) ? json_decode(file_get_contents(self::$storageFile), true) : [];

        foreach ($data as $ip => $entry) {
            if ($entry['timestamp'] + self::$timeFrame < time()) {
                unset($data[$ip]);
            }
        }

        if (!isset($data[$userIP])) {
            $data[$userIP] = ['count' => 1, 'timestamp' => time()];
        } else {
            if ($data[$userIP]['count'] >= self::$limit) {
                http_response_code(429);
                exit(json_encode(['error' => 'Rate limit exceeded. Try again later.']));
            }
            $data[$userIP]['count']++;
        }

        file_put_contents(self::$storageFile, json_encode($data));
    }
}
