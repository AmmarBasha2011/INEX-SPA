<?php

/**
 * Webhook Utility.
 *
 * This file contains the Webhook class, a static utility for sending POST
 * requests with JSON payloads to external URLs.
 */

/**
 * A simple class for sending webhook requests.
 *
 * This class provides a static method to send a POST request with a JSON
 * payload to a given URL using cURL. It's designed for simple integrations
 * and notifications to external services.
 */
class Webhook
{
    /**
     * Sends a JSON payload to a specified webhook URL via a POST request.
     *
     * This method validates the provided URL, JSON-encodes the data array,
     * and sends it to the target URL using cURL.
     *
     * @param string $url  The fully qualified URL to send the webhook request to.
     * @param array  $data An associative array of data to be sent as the JSON payload.
     *
     * @return bool|string The response body from the server on success, or false if the URL is invalid.
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
