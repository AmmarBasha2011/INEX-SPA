<?php

/**
 * A utility class for sending outbound webhook requests.
 *
 * This class provides a simple, static method to send a POST request with a
 * JSON payload to a specified URL, which is useful for notifying external
 * services about events in the application.
 */
class Webhook
{
    /**
     * Sends a JSON payload via a POST request to a specified webhook URL.
     *
     * This method first validates the provided URL. It then encodes the given data
     * array into a JSON string and sends it as the body of a POST request, with
     * the appropriate `Content-Type` and `Content-Length` headers.
     *
     * @param string $url  The destination URL for the webhook request.
     * @param array  $data An associative array of data that will be JSON-encoded
     *                     and sent as the request payload.
     *
     * @return bool|string Returns the response body from the server on success.
     *                     Returns `false` if the URL is invalid or if a cURL
     *                     error occurs during the request.
     */
    public static function send($url, $data = [])
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '.strlen($payload),
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
