<?php

/**
 * A class for sending webhooks.
 */
class Webhook
{
    /**
     * Sends a webhook to the specified URL.
     *
     * @param string $url  The URL to send the webhook to.
     * @param array  $data The data to send with the webhook.
     *
     * @return bool|string The response from the webhook, or false on failure.
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
