<?php

/**
 * A utility class for sending outbound webhook requests.
 *
 * This class provides a simple, static method to send a POST request with a
 * JSON payload to a specified URL. It is useful for notifying external services
 * about events occurring within the application, such as a new user registration
 * or a completed order.
 */
class Webhook
{
    /**
     * Sends a JSON payload via a POST request to a specified webhook URL.
     *
     * This method first validates that the provided URL is well-formed. It then
     * encodes the given data array into a JSON string and sends it as the body of
     * a POST request using cURL. The appropriate `Content-Type` and `Content-Length`
     * headers are set automatically.
     *
     * @param string $url  The destination URL for the webhook request. This must be a
     *                     valid and fully qualified URL.
     * @param array  $data (Optional) An associative array of data that will be JSON-encoded
     *                     and sent as the request payload. Defaults to an empty array.
     *
     * @return string|bool Returns the response body from the server on a successful request.
     *                     Returns `false` if the provided URL is invalid or if a cURL
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
