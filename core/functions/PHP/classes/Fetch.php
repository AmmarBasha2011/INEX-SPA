<?php

class Fetch
{
    private static $defaultHeaders = [];

    public static function setDefaultHeader($name, $value)
    {
        self::$defaultHeaders[$name] = $value;
    }

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
