<?php

/**
 * A basic firewall for blocking requests based on IP addresses and User-Agent strings.
 *
 * This class provides a simple security layer by checking incoming requests against
 * a deny-list defined in a JSON configuration file. If a request matches any of
 * the defined rules, it is blocked and redirected.
 */
class Firewall
{
    /**
     * Inspects the current request and blocks it if it matches any firewall rules.
     *
     * This method reads the firewall configuration from `Json/firewall.json`,
     * which should contain lists of blocked IP addresses and User-Agent strings.
     * It compares the current request's IP and User-Agent against these lists.
     * If a match is found, the `block()` method is called to halt execution.
     *
     * @return void
     */
    public static function check()
    {
        $configPath = __DIR__.'/../../../../Json/firewall.json';

        if (!file_exists($configPath)) {
            return; // No settings found
        }

        $config = json_decode(file_get_contents($configPath), true);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        // Check IP
        if (!empty($config['block_ips']) && in_array($ip, $config['block_ips'])) {
            self::block($config);
        }

        // Check User-Agent
        if (!empty($config['block_user_agents'])) {
            foreach ($config['block_user_agents'] as $ua) {
                if (strpos($agent, strtolower($ua)) !== false) {
                    self::block($config);
                }
            }
        }
    }

    /**
     * Halts the current request and redirects the user to a blocked page.
     *
     * This private helper method is triggered when a firewall rule is matched.
     * It sends a redirect header to the user, pointing them to the URL
     * specified in the `redirect_blocked_to` setting in the firewall config,
     * and then terminates the script.
     *
     * @param array $config The associative array of firewall configuration settings.
     * @return void
     */
    private static function block($config)
    {
        $redirectTo = getEnvValue('WEBSITE_URL').($config['redirect_blocked_to'] ?? 'blocked');
        header("Location: $redirectTo");
        exit;
    }
}
