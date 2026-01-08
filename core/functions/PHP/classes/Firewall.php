<?php

/**
 * A basic firewall for blocking requests based on IP addresses and User-Agent strings.
 *
 * This class provides a simple, static security layer that checks incoming requests
 * against a configurable deny-list defined in a JSON file. If a request's IP address
 * or User-Agent string matches any of the defined rules, the request is blocked
 * and the user is redirected to a designated 'blocked' page.
 */
class Firewall
{
    /**
     * Inspects the current request and blocks it if it matches any defined firewall rules.
     *
     * This method is the main entry point for the firewall. It reads its configuration
     * from `Json/firewall.json`, which should contain `block_ips` and `block_user_agents`
     * arrays. It compares the current request's IP (`$_SERVER['REMOTE_ADDR']`) and
     * User-Agent (`$_SERVER['HTTP_USER_AGENT']`) against these lists. If a match is
     * found, the `block()` method is called to halt execution and redirect the user.
     *
     * @return void This function does not return a value; it either allows the script
     *              to continue or terminates it by calling `block()`.
     */
    public static function check()
    {
        $configPath = __DIR__.'/../../../../Json/firewall.json';

        if (!file_exists($configPath)) {
            return; // No settings found, firewall takes no action.
        }

        $config = json_decode(file_get_contents($configPath), true);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        // Check against blocked IPs
        if (!empty($config['block_ips']) && in_array($ip, $config['block_ips'])) {
            self::block($config);
        }

        // Check against blocked User-Agent substrings
        if (!empty($config['block_user_agents'])) {
            foreach ($config['block_user_agents'] as $ua) {
                if (strpos($agent, strtolower($ua)) !== false) {
                    self::block($config);
                }
            }
        }
    }

    /**
     * Halts the current request and redirects the user to a 'blocked' page.
     *
     * This private helper method is triggered when a firewall rule is matched.
     * It constructs a redirect URL based on the `WEBSITE_URL` environment variable
     * and the `redirect_blocked_to` setting in the firewall configuration. After
     * sending the redirect header, it terminates the script to prevent any further
     * processing of the malicious request.
     *
     * @param array $config The associative array of firewall configuration settings.
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
