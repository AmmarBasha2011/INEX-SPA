<?php

/**
 * A basic firewall for blocking requests based on IP addresses and User-Agent strings.
 *
 * This class provides a simple, file-based security layer by checking incoming requests
 * against a deny-list defined in a JSON configuration file. If a request's IP address
 * or User-Agent string matches any of the defined rules, the request is blocked and
 * the user is redirected to a designated page.
 */
class Firewall
{
    /**
     * Inspects the current request and blocks it if it matches any firewall rules.
     *
     * This method reads the firewall configuration from `Json/firewall.json`. This file
     * should contain lists of blocked IP addresses (`block_ips`) and User-Agent strings
     * (`block_user_agents`). It compares the current request's IP address and User-Agent
     * (case-insensitively) against these lists. If a match is found, the `block()`
     * method is called to halt execution and redirect the user.
     *
     * @return void
     */
    public static function check()
    {
        $configPath = __DIR__.'/../../../../Json/firewall.json';

        if (!file_exists($configPath)) {
            return; // No settings file found, do nothing.
        }

        $config = json_decode(file_get_contents($configPath), true);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        // Check if the current IP address is in the block list.
        if (!empty($config['block_ips']) && in_array($ip, $config['block_ips'])) {
            self::block($config);
        }

        // Check if any part of the User-Agent string is in the block list.
        if (!empty($config['block_user_agents'])) {
            foreach ($config['block_user_agents'] as $ua) {
                if (strpos($agent, strtolower($ua)) !== false) {
                    self::block($config);
                }
            }
        }
    }

    /**
     * Halts the current request and redirects the user to a "blocked" page.
     *
     * This private helper method is triggered when a firewall rule is matched.
     * It sends an HTTP redirect header to the user, pointing them to the URL
     * specified in the `redirect_blocked_to` setting in the firewall configuration,
     * and then terminates the script to prevent further processing.
     *
     * @param array $config The associative array of firewall configuration settings,
     *                      used to determine the redirect destination.
     *
     * @return void This method never returns as it terminates the script.
     */
    private static function block($config)
    {
        $redirectTo = getEnvValue('WEBSITE_URL').($config['redirect_blocked_to'] ?? 'blocked');
        header("Location: $redirectTo");
        exit;
    }
}
