<?php

/**
 * Simple IP and User-Agent Firewall.
 *
 * This file contains the Firewall class, a static utility for blocking incoming
 * web requests based on a predefined set of rules.
 */

/**
 * A simple firewall to block requests based on IP and User-Agent.
 *
 * This class reads a configuration file (`Json/firewall.json`) to check
 * incoming requests against a list of blocked IP addresses and User-Agent strings.
 * If a match is found, the request is blocked and redirected. This class is
 * entirely static.
 */
class Firewall
{
    /**
     * Checks the current request against the firewall rules.
     *
     * This is the main entry point for the firewall. It retrieves the client's
     * IP address and User-Agent, then compares them against the rules defined
     * in `Json/firewall.json`. If a rule is matched, the `block()` method is called.
     *
     * @return void
     */
    public static function check()
    {
        $configPath = __DIR__.'/../../../../Json/firewall.json';

        if (!file_exists($configPath)) {
            return; // Firewall is inactive if no settings file is found.
        }

        $config = json_decode(file_get_contents($configPath), true);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        // Check against blocked IP addresses.
        if (!empty($config['block_ips']) && in_array($ip, $config['block_ips'])) {
            self::block($config);
        }

        // Check against blocked User-Agent strings (case-insensitive).
        if (!empty($config['block_user_agents'])) {
            foreach ($config['block_user_agents'] as $ua) {
                if (strpos($agent, strtolower($ua)) !== false) {
                    self::block($config);
                }
            }
        }
    }

    /**
     * Blocks the current request and performs a redirect.
     *
     * This method is called when a firewall rule is triggered. It constructs a
     * redirection URL based on the `redirect_blocked_to` setting in the
     * firewall configuration and then terminates the script execution.
     *
     * @param array $config The firewall configuration array.
     *
     * @return void
     */
    private static function block($config)
    {
        $redirectTo = getEnvValue('WEBSITE_URL').($config['redirect_blocked_to'] ?? 'blocked');
        header("Location: $redirectTo");
        exit;
    }
}
