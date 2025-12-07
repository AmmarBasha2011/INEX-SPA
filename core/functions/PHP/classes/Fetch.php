<?php

/**
 * A class for making HTTP requests using cURL.
 */
class Fetch
{
    /**
     * Default headers to be sent with every request.
     *
     * @var array
     */
    private static $defaultHeaders = [];

    /**
     * Sets a default header to be sent with every request.
     *
     * @param string $name  The name of the header.
     * @param string $value The value of the header.
     */
    public static function setDefaultHeader($name, $value)
    {
        self::$defaultHeaders[$name] = $value;
    }

    /**
     * Makes an HTTP request.
     *
     * @param string $url     The URL to make the request to.
     * @param array  $options An array of options for the request.
     *                        - method: The HTTP method to use (e.g., 'GET', 'POST').
     *                        - headers: An array of headers to send with the request.
     *                        - body: The body of the request.
     *                        - retries: The number of times to retry the request on failure.
     *                        - retryDelay: The delay in milliseconds between retries.
     *                        - cache: The number of seconds to cache the response.
     *                        - auth: Whether to send the authentication token.
     *
     * @return array An array containing the response body, status code, and any errors.
     */
    public static function fetch($url, $options = [])
    {
        $method = $options['method'] ?? 'GET';
        $headers = array_merge(self::$defaultHeaders, $options['headers'] ?? []);
        $body = $options['body'] ?? null;
        $retries = $options['retries'] ?? 0;
        $retryDelay = $options['retryDelay'] ?? 1000;
        $cacheTTL = $options['cache'] ?? 0;
        $auth = $options['auth'] ?? true;

        if ($cacheTTL > 0 && function_exists('getCache')) {
            $cacheKey = 'fetch_'.md5($url.json_encode($options));
            $cachedResponse = getCache($cacheKey);
            if ($cachedResponse !== null) {
                return $cachedResponse;
            }
        }

        if ($auth && function_exists('getEnvValue')) {
            $token = getEnvValue('AUTH_TOKEN');
            if ($token && !isset($headers['Authorization'])) {
                $headers['Authorization'] = 'Bearer '.$token;
            }
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headerArray = [];
        foreach ($headers as $key => $value) {
            $headerArray[] = "$key: $value";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($body) ? json_encode($body) : $body);
            if (is_array($body)) {
                $headerArray[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            }
        }

        $response = null;
        $httpCode = null;
        $error = null;
        $attempts = 0;

        while ($attempts <= $retries) {
            $attempts++;

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);

            $isCurlError = !empty($error);
            $isServerError = $httpCode >= 500 && $httpCode < 600;

            if (!$isCurlError && !$isServerError) {
                break;
            }

            if ($attempts <= $retries) {
                usleep($retryDelay * 1000);
            }
        }

        curl_close($ch);

        if (!empty($error)) {
            self::logError('cURL Error: '.$error, $url, $options);
        } elseif ($httpCode >= 400) {
            self::logError('HTTP Error: '.$httpCode, $url, $options, $response);
        }

        $result = [
            'body'   => $response,
            'status' => $httpCode,
            'error'  => $error,
        ];

        if ($cacheTTL > 0 && function_exists('setCache') && empty($error) && $httpCode < 400) {
            setCache($cacheKey, $result, $cacheTTL);
        }

        return $result;
    }

    /**
     * Logs an error message.
     *
     * @param string $message  The error message.
     * @param string $url      The URL that was requested.
     * @param array  $options  The options that were used for the request.
     * @param string $response The response from the server.
     */
    private static function logError($message, $url, $options, $response = null)
    {
        $logMessage = '['.date('Y-m-d H:i:s').'] Fetch Error: '.$message."\n";
        $logMessage .= 'URL: '.$url."\n";
        $logMessage .= 'Options: '.json_encode($options)."\n";
        if ($response) {
            $logMessage .= 'Response: '.$response."\n";
        }
        $logMessage .= "-----------------\n";

        $logPath = __DIR__.'/../../logs/fetch.log';
        error_log($logMessage, 3, $logPath);
    }
}
